<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync;

use App\Service\CatalogSync\Syncer\Syncer;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
    private $sync;
    protected $output;

    /**
     * Set-up a new synchronization.
     *
     * @return void
     */
    public function __construct(
        private ContainerInterface $container,
        private string $syncStrategy,
        private string|array $errorMailTo,
        private string $errorMailFrom,
        private MailerInterface $mailer,
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
     * Set the Output iterface to write status lines to.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
        $this->getSync()->setOutput($output);
    }

    private function getSync(): Syncer
    {
        if (!$this->sync) {
            $this->sync = $this->container->get($this->syncStrategy);
        }

        return $this->sync;
    }

    /**
     * Run the synchronization.
     *
     * @return void
     */
    public function sync()
    {
        try {
            $this->getSync()->connect();
            $this->getSync()->preCopy();
            $this->getSync()->copy();
            $this->getSync()->postCopy();
            $this->getSync()->updateDerived();
            $this->getSync()->disconnect();
            if (!empty($this->getSync()->getNonFatalErrors())) {
                $this->sendNonFatalErrorMessage($this->getSync()->getNonFatalErrors());
            }
        } catch (\Exception $e) {
            $this->getSync()->rollback();
            if (!empty($this->getSync()->getNonFatalErrors())) {
                $this->sendNonFatalErrorMessage($this->getSync()->getNonFatalErrors());
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
            $this->getSync()->connect();
            $this->getSync()->updateDerived();
            $this->getSync()->disconnect();
        } catch (\Exception $e) {
            $this->getSync()->rollback();
            $this->sendException($e);
            throw $e;
        }
    }

    /**
     * Send messages to administrators on fatal exceptions.
     *
     * @return null
     */
    protected function sendException(\Exception $e)
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
        $errorMailTo = [];
        if (is_string($this->errorMailTo)) {
            $errorMailTo[] = $this->errorMailTo;
        } else {
            foreach ($this->errorMailTo as $email) {
                $errorMailTo[] = $email;
            }
        }
        $host = trim(shell_exec('hostname'));
        $email = (new Email())
            ->from($this->errorMailFrom)
            ->subject("$host - COURSE CATALOG: $subject")
            ->text($message);
        foreach ($errorMailTo as $address) {
            $email->addTo($address);
        }
        $this->mailer->send($email);
    }
}
