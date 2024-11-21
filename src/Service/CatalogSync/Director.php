<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync;

use App\Service\CatalogSync\Syncer\Syncer;

/**
 * This is an abstract class that should be extended by any controller that needs
 * access to the the OSID course manager or runtime manager.
 *
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Director
{
    /**
     * Set-up a new synchronization.
     *
     * @return void
     */
    public function __construct(
        private Syncer $sync,
        private string|array $errorMailTo,
        private string $errorMailFrom,
    ) {
        if (is_array($errorMailTo)) {
            foreach ($errorMailTo as $email) {
                if (!filter_var($email, \FILTER_VALIDATE_EMAIL)) {
                    throw new \InvalidArgumentException("errorMailTo, '$email', is not a valid email address.");
                }
            }
        } else {
            if (!filter_var($errorMailTo, \FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("errorMailTo, '".$errorMailTo."', is not a valid email address.");
            }
        }
        // From:
        if (!filter_var($errorMailFrom, \FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("errorMailFrom, '".$errorMailFrom."', is not a valid email address.");
        }
    }

    /**
     * Run the synchronization.
     *
     * @return void
     */
    public function sync()
    {
        try {
            $this->sync->connect();
            $this->sync->preCopy();
            $this->sync->copy();
            $this->sync->postCopy();
            $this->sync->updateDerived();
            $this->sync->disconnect();
            if (!empty($this->sync->getNonFatalErrors())) {
                $this->sendNonFatalErrorMessage($this->sync->getNonFatalErrors());
            }
        } catch (Exception $e) {
            $this->sync->rollback();
            if (!empty($this->sync->getNonFatalErrors())) {
                $this->sendNonFatalErrorMessage($this->sync->getNonFatalErrors());
            }
            $this->sendException($e);
            throw $e;
        }
    }

    /**
     * Only updated the derived tables with existing data.
     * Sometimes useful for fixing interim data errors.
     */
    public function updateDerived()
    {
        try {
            $this->sync->connect();
            $this->sync->updateDerived();
            $this->sync->disconnect();
        } catch (Exception $e) {
            $this->sync->rollback();
            $this->sendException($e);
            throw $e;
        }
    }

    /**
     * Send messages to administrators on fatal exceptions.
     *
     * @return null
     */
    protected function sendException(Exception $e)
    {
        $host = trim(shell_exec('hostname'));
        $subject = 'Synchonization Exception';
        $message = "The following errors occurred during database synchronization on $host:\n\n";
        $message .= $e->getMessage()."\n\n";
        $message .= $e->getTraceAsString()."\n\n";
        $this->sendAdminMessage($subject, $message);
    }

    /**
     * Send messages to administrators on non-fatal errors.
     *
     * @return null
     */
    protected function sendNonFatalErrorMessage($errors)
    {
        $host = trim(shell_exec('hostname'));
        $subject = 'Non-Fatal Errors During Synchonization';
        $message = "The following non-fatal errors occurred during database synchronization on $host:\n\n";
        $message .= implode("\n\n", $errors);
        $message .= "\n\n";
        $this->sendAdminMessage($subject, $message);
    }

    /**
     * Send messages to administrators on errors.
     *
     * @param string $subject
     * @param string $message
     *
     * @return null
     */
    protected function sendAdminMessage($subject, $message)
    {
        if (empty($this->errorMailTo)) {
            return;
        }
        if (is_string($this->errorMailTo)) {
            $to = $this->errorMailTo;
        } else {
            $errorMailTo = [];
            foreach ($this->errorMailTo as $email) {
                $errorMailTo[] = $email;
            }
            $to = implode(', ', $errorMailTo);
        }
        $host = trim(shell_exec('hostname'));
        $subject = "$host - COURSE CATALOG: $subject";

        $headers = 'From: '.$this->errorMailFrom."\r\n";
        mail($to, $subject, $message, $headers);
    }
}
