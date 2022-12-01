<?php
/**
 * @since 2/22/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This is an abstract class that should be extended by any controller that needs
 * access to the the OSID course manager or runtime manager.
 *
 * @since 2/22/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CatalogSync_Director
{
	protected $config;
	protected $sync;

	/**
	 * Set-up a new synchronization.
	 *
	 * @param Zend_Config $config
	 * @return void
	 * @access public
	 */
	public function __construct (Zend_Config $config) {
		$this->config = $this->validateConfig($config);
		$sync_class = $this->config->sync_class;
		$this->sync = new $sync_class();
		if (!$this->sync instanceof CatalogSync_Syncer) {
			throw new Exception("sync_class ".$sync_class." doesn't implement the required CatalogSync_Syncer.");
		}
		$this->sync->configure($this->config);
	}

	/**
	 * Run the synchronization
	 *
	 * @return void
	 * @access public
	 */
	public function sync () {
		try {
			$this->sync->connect();
			$this->sync->preCopy();
			$this->sync->copy();
			$this->sync->postCopy();
			$this->sync->updateDerived();
			$this->sync->disconnect();
		} catch (Exception $e) {
			$this->sync->rollback();
			$this->sendException($e);
			throw $e;
		}
	}

	/**
	 * Only updated the derived tables with existing data.
	 * Sometimes useful for fixing interim data errors.
	 */
	public function updateDerived () {
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
	 * Validate our configuration.
	 *
	 * @param Zend_Config $config
	 * @return Zend_Config
	 * @access protected
	 */
	protected function validateConfig (Zend_Config $config) {
		// Validate that our sync_class is defined.
		if (empty($config->sync_class)) {
			throw new Exception('sync_class is missing from the CatalogSync configuration.');
		}
		// Error mail-sending addresses -- Only needed if we have at least one To: address.
		if (!empty($this->config->error_mail_to)) {
			// To:
			if (is_array($this->config->error_mail_to)) {
				foreach ($this->config->error_mail_to as $email) {
					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						throw new Exception("error_mail_to, '$email', is not a valid email address.");
					}
				}
			} else {
				if (!filter_var($this->config->error_mail_to, FILTER_VALIDATE_EMAIL)) {
					throw new Exception("error_mail_to, '".$this->config->error_mail_to."', is not a valid email address.");
				}
			}
			// From:
			if (!filter_var($this->config->error_mail_from, FILTER_VALIDATE_EMAIL)) {
				throw new Exception("error_mail_from, '".$this->config->error_mail_from."', is not a valid email address.");
			}
		}
		return $config;
	}

	/**
	 * Send messages to administrators on errors.
	 *
	 * @param Exception $exception
	 * @return null
	 */
	protected function sendException(Exception $e) {
		if (empty($this->config->error_mail_to)) {
			return;
		}
		if (is_string($this->config->error_mail_to)) {
			$to = $this->config->error_mail_to;
		} else {
			$error_mail_to = array();
			foreach ($this->config->error_mail_to as $email) {
				$error_mail_to[] = $email;
			}
			$to = implode(", ", $error_mail_to);
		}
		$host = trim(shell_exec('hostname'));
		$subject = "$host - COURSE CATALOG: Synchonization Exception";
		$message = "The following errors occurred during database synchronization on $host:\n\n";
		$message .= $e->getMessage() . "\n\n";
		$message .= $e->getTraceAsString() . "\n\n";

		$headers = "From: ".$this->config->error_mail_from."\r\n";
		mail($to, $subject, $message, $headers);
	}

}
