<?php

namespace App\Service\Osid;

use Catalog\OsidImpl\Middlebury\configuration\ArrayValueLookupSession;
use Catalog\OsidImpl\Middlebury\OsidRuntimeManager;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * A helper to provide access to the CourseManager OSID and OSID configuration.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Runtime
{
    private \osid_OsidRuntimeManager $runtimeManager;
    private \osid_course_CourseManager $courseManager;
    private \osid_configuration_ValueLookupSession $configuration;

    /**
     * Create a new OSID service instance.
     *
     * @param string $courseImpl
     *                           The OSID CourseManager implementation class to use
     */
    public function __construct(
        array $config,
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
        private string $courseImpl = 'banner_course_CourseManager',
        private ?CacheItemPoolInterface $cache = null,
    ) {
        $this->configuration = new ArrayValueLookupSession(
            new \phpkit_id_Id('localhost', 'urn', 'symfony_configuration'),
            $config
        );
    }

    /**
     * Answer the CourseManager.
     *
     * @return osid_course_CourseManager
     *
     * @since 4/20/09
     */
    public function getCourseManager()
    {
        if (!isset($this->courseManager)) {
            if (class_exists($this->courseImpl)) {
                $runtimeManager = $this->getRuntimeManager();
                $this->courseManager = $runtimeManager->getManager(\osid_OSID::COURSE(), $this->courseImpl, '3.0.0');

                // Inject our cache implementation into the course manager.
                if (method_exists($this->courseManager, 'setCache')) {
                    if (empty($this->cache)) {
                        throw new \osid_IllegalStateException('The Course manager needs a cache, but one was not autowired.');
                    }
                    $this->courseManager->setCache($this->cache);
                }
            } else {
                throw new \InvalidArgumentException('Unknown CourseManger implementation class: '.$this->courseImpl);
            }
        }

        return $this->courseManager;
    }

    /**
     * Answer the Runtime Manager.
     *
     * @return osid_OsidRuntimeManager
     *
     * @since 4/20/09
     */
    public function getRuntimeManager()
    {
        if (!isset($this->runtimeManager)) {
            $this->runtimeManager = new OsidRuntimeManager($this->configuration);
        }

        return $this->runtimeManager;
    }
}
