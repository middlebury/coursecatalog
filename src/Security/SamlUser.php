<?php

namespace App\Security;

use Nbgrp\OneloginSamlBundle\Security\User\SamlUserInterface;

class SamlUser implements SamlUserInterface
{
    private $email;
    private $givenName;
    private $surname;
    private $assignedRoles = [];

    public function __construct(
        private string $id,
    ) {
    }

    public function setSamlAttributes(array $attributes): void
    {
        if (!empty($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'][0])) {
            $this->email = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'][0];
        }
        if (!empty($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'][0])) {
            $this->givenName = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'][0];
        }
        if (!empty($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname'][0])) {
            $this->surname = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname'][0];
        }
        // Add a custom attribute called 'AssignedRoles' and supply zero or more
        // of the following values in the SAML response:
        // "App.EmailSendAllowed" -- If included, the user will be able to send
        //                           their schedule as an email.
        // "App.Manager" -- If included, the user will be able to manage term
        //                  visibility and export configurations.
        if (!empty($attributes['AssignedRoles'])) {
            $this->assignedRoles = $attributes['AssignedRoles'];
        }
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        if (!empty($this->assignedRoles) && in_array('App.EmailSendAllowed', $this->assignedRoles)) {
            $roles[] = 'ROLE_CAN_SEND_EMAIL';
        }
        if (!empty($this->assignedRoles) && in_array('App.Manager', $this->assignedRoles)) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
        // Nothing to do.
    }

    /**
     * Returns the identifier for this user (e.g. username or email address).
     */
    public function getUserIdentifier(): string
    {
        return $this->id;
    }

    /**
     * Returns a name for the user.
     */
    public function getName(): string
    {
        if ($this->givenName || $this->surname) {
            return trim($this->givenName.' '.$this->surname);
        } elseif ($this->email) {
            return $this->email;
        } else {
            return $this->id;
        }
    }

    /**
     * Answer the email address for the user.
     */
    public function getEmail(): string
    {
        if ($this->email) {
            return $this->email;
        } else {
            throw new \Exception('No email is set for user '.$this->id);
        }
    }
}
