# Marker to tell the VCL compiler that this VCL has been adapted to the
# new 4.0 format.
vcl 4.0;

import directors;
import std;
#acl middlebury_network {
#    "140.233.0.0"/16;   # Middlebury
#    "172.16.0.0"/16;    # MIIS
#}

# Pass PRTG monitoring through  in vcl_recv so that we can detect downtime and aren't just monitoring cache hits.
acl monitoring {
    "140.233.1.32";    # PRTG
}

sub vcl_recv {

    # Block requests we don't like.
#    if (
#        client.ip !~ middlebury_network &&
#        req.http.host !~ "catalog\.?middlebury\.edu" &&
#        req.http.host !~ "catalog\.?miis\.edu" &&
#        req.http.host !~ ".+\.lndo\.site"
#    ) {
#        return (synth(418, "Unexpected Domain"));
#    }

    # Strip out an X-Forwarded-Proto header from any host other than ourselves.
    if (req.http.X-Forwarded-Proto ~ ".+" && client.ip != "127.0.0.1" && client.ip != "::1") {
        unset req.http.X-Forwarded-Proto;
    }

    # Strip out an X-Forwarded-For header from any host other than ourselves and reset to the client IP.
    if (req.http.X-Forwarded-For ~ ".+" && client.ip != "127.0.0.1" && client.ip != "::1") {
        set req.http.X-Forwarded-For = client.ip;
    }

    # Set an X-Forwarded-Client to just the forwarded client IP (and not the full chain)
    set req.http.X-Forwarded-Client = regsub(req.http.X-Forwarded-For, "^([a-f0-9:.]+)(, *[a-f0-9:.]+)*", "\1");

    # Grace configuration from:
    # http://info.varnish-software.com/blog/grace-varnish-4-stale-while-revalidate-semantics-varnish
    set req.http.X-Varnish-Grace = "none";

    # Pipe through on-RFC2616 or CONNECT requests without modification.
    if (req.method != "GET" &&
        req.method != "HEAD" &&
        req.method != "PUT" &&
        req.method != "POST" &&
        req.method != "TRACE" &&
        req.method != "OPTIONS" &&
        req.method != "DELETE"
    ) {
        return (pipe);
    }

    # We only cache GET and HEAD by default, pass through POST and others without caching.
    if (req.method != "GET" && req.method != "HEAD") {
        return (pass);
    }

    # If the request doesn't have a cookie that matches the Drupal SESSaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    # (where aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa is a 32 character hash) format, then strip all cookies
    # from the request to prevent spurious cache misses.
    #
    # The following statement is based on
    # http://www.lullabot.com/articles/varnish-multiple-web-servers-drupal
    #
    # Remove all cookies that Drupal doesn't need to know about. ANY remaining
    # cookie will cause the request to pass-through to Apache. The session cookie allows
    # all authenticated users to pass through as long as they're logged in.
    if (req.http.Cookie) {
        set req.http.Cookie = ";" + req.http.Cookie;
        set req.http.Cookie = regsuball(req.http.Cookie, "; +", ";");
        # Add any additional cookies as an OR with SESS. E.g.
        # ";(SESS[a-z0-9]+|NO_CACHE|something_else)="
        set req.http.Cookie = regsuball(req.http.Cookie, ";(PHPSESSID)=", "; \1=");
        set req.http.Cookie = regsuball(req.http.Cookie, ";[^ ][^;]*", "");
        set req.http.Cookie = regsuball(req.http.Cookie, "^[; ]+|[; ]+$", "");
        if (req.http.Cookie == "") {
            # If there are no remaining cookies, remove the cookie header. If there
            # aren't any cookie headers, Varnish's default behavior will be to cache
            # the page.
            unset req.http.Cookie;
        }
    }

    # Remove a ";" prefix, if present.
    set req.http.Cookie = regsub(req.http.Cookie, "^;\s*", "");
    # Remove empty cookies.
    if (req.http.Cookie ~ "^\s*$") {
        unset req.http.Cookie;
    }

    # Skip the Varnish cache for authenticated requests
    if (req.http.Authorization || req.http.Cookie) {
    # Authenticated requests are not cacheable by default
        return (pass);
    }

    # Normalize the Accept-Encoding header
    # as per: http://varnish-cache.org/wiki/FAQ/Compression
    if (req.http.Accept-Encoding) {
        if (req.url ~ "\.(jpg|png|gif|gz|tgz|bz2|tbz|mp3|ogg)$") {
            # No point in compressing these
            unset req.http.Accept-Encoding;
        }
        elsif (req.http.Accept-Encoding ~ "gzip") {
            set req.http.Accept-Encoding = "gzip";
        }
        else {
            # Unknown or deflate algorithm
            unset req.http.Accept-Encoding;
        }
    }

    # Pass PRTG monitoring through so that we can detect downtime and aren't just monitoring cache hits.
    if (client.ip ~ monitoring || (req.http.X-Forwarded-Client ~ ".+" && std.ip(req.http.X-Forwarded-Client, "0.0.0.0") ~ monitoring)) {
        return (pass);
    }

    return (hash);
}

sub vcl_hash {
    hash_data(req.url);
    if (req.http.host) {
        hash_data(req.http.host);
    } else {
        hash_data(server.ip);
    }
    if (req.http.Cookie) {
        hash_data(req.http.Cookie);
    }
    // Ensure that we cache HTTP/HTTPS request separately as one might be a redirect
    // to the other.
    if (req.http.X-Forwarded-Proto) {
        hash_data(req.http.X-Forwarded-Proto);
    }
    return (lookup);
}

sub vcl_hit {
    # Grace configuration from:
    # http://info.varnish-software.com/blog/grace-varnish-4-stale-while-revalidate-semantics-varnish
    if (obj.ttl >= 0s) {
        # normal hit
        return (deliver);
    }
    # We have no fresh fish. Lets look at the stale ones.
    if (std.healthy(req.backend_hint)) {
        # Backend is healthy. Limit age to 10s.
        if (obj.ttl + 10s > 0s) {
            set req.http.X-Varnish-Grace = "normal(limited)";
            return (deliver);
        } else {
            # No candidate for grace. Fetch a fresh object.
            return(miss);
        }
    } else {
        # backend is sick - use full grace
        if (obj.ttl + obj.grace > 0s) {
            set req.http.grace = "full";
            return (deliver);
        } else {
            # no graced object.
            return(miss);
        }
    }
}

sub vcl_deliver {
    # Grace configuration from:
    # http://info.varnish-software.com/blog/grace-varnish-4-stale-while-revalidate-semantics-varnish
    set resp.http.X-Varnish-Grace = req.http.grace;
}

sub vcl_backend_response {

    if (beresp.ttl > 0s) {
        # Give the client a short TTL so the browser doesn't cache it a long time.
        # This means that when the stories cache is cleared, clients won't wait a full
        # 5 minutes before checking for the new version.
        set beresp.http.cache-control = "max-age=15";

        # Give clients a slightly longer time before checking back in for images.
        if (bereq.url ~ "\.(png|gif|jpg)$" && bereq.url !~ "^/cas\?") {
            set beresp.http.cache-control = "max-age=300";
        }
    }

    # Strip any cookies before an image/js/css is inserted into cache.
    if (bereq.url ~ "\.(png|gif|jpg|swf|css|js)$" && bereq.url !~ "^/cas\?") {
        unset beresp.http.set-cookie;
    }

    # If the back-end fails do not send this request to it again for
    # a few seconds to let it recover. Restart fetching with another
    # back-end or use grace if no healthy back-ends are available.
    if (beresp.status == 500) {
        if (bereq.method != "POST") {
            return (retry);
        } else {
            return (abandon);
        }
    }
    # Allow serving items from the cache for up to 24 hours if no
    # healthy back-ends can fulfill the requst
    set beresp.grace = 48h;

}

sub vcl_backend_error {
    # Let's deliver a friendlier error page.
    # You can customize this as you wish.
    set beresp.http.Content-Type = "text/html; charset=utf-8";
    synthetic ({"
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 <html>
    <head>
        <title>"} + beresp.status + " " + beresp.reason + {"</title>
        <style type="text/css">
        body {background: #071427; color: #210; font-family: "Helvetica Neue", Arial, Helvetica,sans-serif;font-size: 0.875em; line-height: 1.4;}
        .container {clear:both;margin:0 auto;position:relative;width:960px;}
        .container h1.wordmark {margin: 0 auto;padding:24px 0 18px;width:380px;}
        .taskbar{padding:0px 18px;height:40px;background-color:#EED;width:924px;}
        .pagecontent{margin-top:18px;position:relative;}
        .page{width:924px;background-color:#fff;float:left;padding:18px;}
        </style>
    </head>
    <body>
        <header class="container">
            <h1 id="midd_wordmark" class="wordmark">
                <a class="noborder" href="http://www.middlebury.edu">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAXwAAABuCAYAAAAkjqjuAAAAGXRFWHRTb2Z0d2FyZQBB
ZG9iZSBJbWFnZVJlYWR5ccllPAAAA2hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/
eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+
IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2Jl
IFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAg
ICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5
LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9
IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHht
bG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3Vy
Y2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHht
cE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo0NDQxQjFBQUUzMjA2ODExODA4
M0UyM0E2QTU0ODcxQiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpFRjEzNzQyNzhE
MjExMUU0OThDQjhFQUNEMTAyMEJCMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpF
RjEzNzQyNjhEMjExMUU0OThDQjhFQUNEMTAyMEJCMCIgeG1wOkNyZWF0b3JUb29sPSJB
ZG9iZSBQaG90b3Nob3AgQ1M2IChNYWNpbnRvc2gpIj4gPHhtcE1NOkRlcml2ZWRGcm9t
IHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MDU4MDExNzQwNzIwNjgxMTgwODNBQzI2
NzlFREZFMTMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NDQ0MUIxQUFFMzIwNjgx
MTgwODNFMjNBNkE1NDg3MUIiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4g
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
AElFTkSuQmCC
" />
                </a>
            </h1>
        </header>
        <article id="midd_content" class="pagecontent container">
            <nav id="midd_taskbar" class="taskbar"></nav>
            <section class="page">
                <h1>Page Could Not Be Loaded</h1>
                <p>We're very sorry the page did not load properly. During major sitewide outages, we are automatically notified of downtime. If this is an isolated problem, please <a href="http://go.middlebury.edu/whd">submit a Helpdesk ticket</a>. For any additional questions or concerns, contact the Helpdesk at 802.443.2200.</p>
                <hr />
                <h4>Debug Info:</h4>
                <pre>Status: "} + beresp.status + {"
Response: "} + beresp.reason + {"
XID: "} + bereq.xid + {"
Backend: "} + bereq.backend + {"
Healthy? "} + std.healthy(bereq.backend) + {"</pre>
            </section>
        </article>
    </body>
 </html>
"});
  return (deliver);
}
