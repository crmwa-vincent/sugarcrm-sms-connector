<?php
require_once "include/connectors/sources/ext/rest/rest.php";

class ext_rest_sms extends ext_rest
{
    protected $_has_testing_enabled = false;
    protected $_enable_in_admin_search = false;

    protected $_required_config_fields = array("sms_provider");

    public $defaultSMSProperties = array(
        "sms_provider" => "",
    );
    public $providerProperties = array();

    public function __construct()
    {
        $this->providerProperties = array(
            "CDYNE" => array(
                "assigned_did" => "",
                "license_key" => "",
            ),
            "MobiWeb" => array(
                "ip_address" => "",
                "username" => "",
                "password" => "",
                "originator" => "",
            ),
            "SMSGlobal" => array(
                "username_sms_global" => "",
                "password_sms_global" => "",
            ),
        );
        parent::__construct();
    }

    public function getProviderProperties()
    {
        return $this->providerProperties;
    }

    public function getItem($args = array(), $module = null)
    {
    }

    public function getList($args = array(), $module = null)
    {
    }

    public function loadConfig()
    {
        $config = array();
        $dir = str_replace('_', '/', get_class($this));
        foreach (SugarAutoLoader::existingCustom("modules/Connectors/connectors/sources/{$dir}/config.php") as $file) {
            require $file;
        }

        /** Bug Fix for where the saveConfig removes the config properties */
        if (isset($config["properties"])) {
            foreach ($this->defaultSMSProperties as $key => $value) {
                if (!array_key_exists($key, $config["properties"])) {
                    $config["properties"][$key] = $value;
                }
            }
        } else {
            $config["properties"] = $this->defaultSMSProperties;
        }

        $this->_config = $config;

        //If there are no required config fields specified, we will default them to all be required
        if (empty($this->_required_config_fields) || empty($this->_required_config_fields_for_button)) {
            foreach ($this->_config['properties'] as $id => $value) {
                $this->_required_config_fields[] = $id;
                $this->_required_config_fields_for_button[] = $id;
            }
        }
    }
}