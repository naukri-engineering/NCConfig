<?php
class NCConfigFactory {
	private static $instance;
	private function __construct() {
		self::$instance = null;
	}
	public static function getInstance() {
		if (!isset(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}
	/* Database Connection */
	private function getDBConnection() {
		return new DBConnection();
	}
	/* Server */
	public function getServerManager() {
		$objServerStore = $this->getServerStore();
		return new ServerManager($objServerStore);
	}
	private function getServerStore() {
		$objDBConnection = $this->getDBConnection();
		return new ServerStore($objDBConnection);
	}
	public function getServerValidator() {
		return new ServerValidator();
	}
	/* ServiceType */
	public function getServiceTypeManager() {
		$objServiceTypeStore = $this->getServiceTypeStore();
		return new ServiceTypeManager($objServiceTypeStore);
	}
	private function getServiceTypeStore() {
		$objDBConnection = $this->getDBConnection();
		return new ServiceTypeStore($objDBConnection);
	}
	public function getServiceTypeValidator() {
		return new ServiceTypeValidator();
	}
	/* Service */
	public function getServiceManager() {
		$objServiceStore = $this->getServiceStore();
		return new ServiceManager($objServiceStore);
	}
	private function getServiceStore() {
		$objDBConnection = $this->getDBConnection();
		return new ServiceStore($objDBConnection);
	}
	public function getServiceValidator() {
		return new ServiceValidator();
	}
	/* Application */
	public function getApplicationManager() {
		$objApplicationStore = $this->getApplicationStore();
		return new ApplicationManager($objApplicationStore);
	}
	private function getApplicationStore() {
		$objDBConnection = $this->getDBConnection();
		return new ApplicationStore($objDBConnection);
	}
	public function getApplicationValidator() {
		return new ApplicationValidator();
	}
	/* System User */
	public function getSystemUserManager() {
		$objSystemUserStore = $this->getSystemUserStore();
		return new SystemUserManager($objSystemUserStore);
	}
	private function getSystemUserStore() {
		$objDBConnection = $this->getDBConnection();
		return new SystemUserStore($objDBConnection);
	}
	public function getSystemUserValidator() {
		return new SystemUserValidator();
	}
	/* Configuration */
	public function getConfigurationManager() {
		$objConfigurationStore = $this->getConfigurationStore();
		return new ConfigurationManager($objConfigurationStore);
	}
	private function getConfigurationStore() {
		$objDBConnection = $this->getDBConnection();
		return new ConfigurationStore($objDBConnection);
	}
	public function getConfigurationValidator() {
		return new ConfigurationValidator();
	}
    /* User */
    public function getUserManager() {
        $objUserStore = $this->getUserStore();
        return new UserManager($objUserStore);
    }
    private function getUserStore() {
        $objDBConnection = $this->getDBConnection();
        return new UserStore($objDBConnection);
    }
    public function getUserValidator() {
        return new UserValidator();
    }
    /* ConfigurationPath */
    public function getConfigurationPathManager() {
        $objConfigurationPathStore = $this->getConfigurationPathStore();
        return new ConfigurationPathManager($objConfigurationPathStore);
    }
    private function getConfigurationPathStore() {
        $objDBConnection = $this->getDBConnection();
        return new ConfigurationPathStore($objDBConnection);
    }
    public function getConfigurationPathValidator() {
        return new ConfigurationPathValidator();
    }
    /* ConfigurationFile */
    public function getConfigurationFileManager() {
        $objConfigurationFileStore = $this->getConfigurationFileStore();
        return new ConfigurationFileManager($objConfigurationFileStore);
    }
    private function getConfigurationFileStore() {
        $objDBConnection = $this->getDBConnection();
        return new ConfigurationFileStore($objDBConnection);
    }
    public function getConfigurationFileValidator() {
        return new ConfigurationFileValidator();
    }
    /* ApplicationServerMap */
    public function getApplicationServerMapManager() {
        $objApplicationServerMapStore = $this->getApplicationServerMapStore();
        return new ApplicationServerMapManager($objApplicationServerMapStore);
    }
    private function getApplicationServerMapStore() {
        $objDBConnection = $this->getDBConnection();
        return new ApplicationServerMapStore($objDBConnection);
    }
    public function getApplicationServerMapValidator() {
        return new ApplicationServerMapValidator();
    }
    /* ReleaseLog */
    public function getReleaseLogManager() {
        $objReleaseLogStore = $this->getReleaseLogStore();
        return new ReleaseLogManager($objReleaseLogStore);
    }
    private function getReleaseLogStore() {
        $objDBConnection = $this->getDBConnection();
        return new ReleaseLogStore($objDBConnection);
    }
    public function getReleaseLogValidator() {
        return new ReleaseLogValidator();
    }
    /* Report */
    public function getReportManager() {
        $objReportStore = $this->getReportStore();
        return new ReportManager($objReportStore);
    }
    private function getReportStore() {
        $objDBConnection = $this->getDBConnection();
        return new ReportStore($objDBConnection);
    }
    public function getReportValidator() {
        return new ReportValidator();
    }
    /* Cron */
    public function getCronManager() {
        $objCronStore = $this->getCronStore();
        return new CronManager($objCronStore);
    }
    private function getCronStore() {
        $objDBConnection = $this->getDBConnection();
        return new CronStore($objDBConnection);
    }
    public function getCronValidator() {
        return new CronValidator();
    }
	/* Authenticate */
	public function getAuthenticateManager() {
		$objDBConnection = $this->getDBConnection();
		return new AuthenticateManager($objDBConnection);
	}
	/* RoleManager */
	public function getRoleManager() {
		return new RoleManager();
	}
}
?>
