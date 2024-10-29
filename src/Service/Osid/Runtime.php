<?php

namespace App\Service\Osid;

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
    private $runtimeManager;
    private $courseManager;
    private $configPath;
    private $courseImpl;

    /**
     * Create a new OSID service instance.
     *
     * @param string $configPath
     *                           The path to the Osid Configuration XML file
     * @param string $courseImpl
     *                           The OSID CourseManager implementation class to use
     */
    public function __construct(
        string $configPath,
        string $courseImpl = 'banner_course_CourseManager',
    ) {
        $this->setConfigPath($configPath);
        $this->courseImpl = $courseImpl;
    }

    /**
     * Answer the configuration path.
     *
     * @return string
     *
     * @since 6/11/09
     */
    public function getConfigPath()
    {
        if (!isset($this->configPath)) {
            $this->configPath = BASE_PATH.'/configuration.plist';
        }

        return $this->configPath;
    }

    /**
     * Set the configuration path.
     *
     * @param string $path
     *
     * @since 6/11/09
     *
     * @throws osid_IllegalStateException the config path has already been set
     */
    public function setConfigPath($path)
    {
        if (isset($this->configPath) && $this->configPath != $path) {
            throw new \osid_IllegalStateException('the config path has already been set');
        }

        $this->configPath = $path;
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
            $this->runtimeManager = new \phpkit_AutoloadOsidRuntimeManager($this->getConfigPath());
        }

        return $this->runtimeManager;
    }
}
