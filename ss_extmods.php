<?php
namespace PHSMGB\ss_extmods;
use \REDCap as REDCap;
use \ExternalModules\ExternalModules as EM;
use \ExternalModules\AbstractExternalModule as AEM;

class ss_extmods extends \ExternalModules\AbstractExternalModule
{
    function redcap_every_page_top($project_id)
    {
//        print "<pre>";
        $ext_mod_page_parts = explode("/",PAGE);
        $ext_mod_page_w_pid = $ext_mod_page_parts[2] . "/" . $ext_mod_page_parts[3] . "/" . $ext_mod_page_parts[4];
        $ext_mod_page = explode("?",$ext_mod_page_w_pid);

        global $Proj;

        if($ext_mod_page[0] == "ExternalModules/manager/project.php") {
            // Hide Self Service module from external module screen
            $script = <<<SCRIPT
<script type="text/javascript">

$( document ).ready(function() {
        $('#external-modules-enabled tbody tr[data-module="self_service_ext_mod"]').remove();
    });
            </script>
SCRIPT;
            print $script;
            //
        }
        if(PAGE == "ProjectSetup/index.php"){
            // Check if auto enable has already been done
            $enable_once_flag = EM::getProjectSetting('self_service_ext_mod', $Proj->project["project_id"], 'enable_once');
            // Calculate the minutes since project creation. Modules should only be transferred once, right after project creation.
            $now = date('Y-m-d G:i:s');
            $date1 = strtotime($now);
            $date2 = strtotime($Proj->project["creation_time"]);
            $diff = abs($date2 - $date1);
            $minutes_since_creation =  $diff/60;

            if (is_null($enable_once_flag) && // this flag allows it to be enable only once on new projects
                $minutes_since_creation <= 3 && // this flag protects old projects from being modified after the module is enabled
                isset($Proj->project["template_id"]) &&
                sizeof($Proj->project["template_id"]) > 0) {

                $mod_on_template = EM::getEnabledModules($Proj->project["template_id"]);

                if(sizeof($mod_on_template) >= 1){

                        // Auto enable template's modules and their settings

                        $sql = "SELECT * FROM redcap_external_module_settings WHERE project_id= {$Proj->project["template_id"]}";
                        $q = db_query($sql);
                        while ($row = db_fetch_assoc($q)) {
//                            var_dump($row);
                            $em_id = $row['external_module_id'];
                            $key = $row['key'];
                            $type = $row['type'];
                            $value = db_real_escape_string($row['value']);
                            $insert_sql = "INSERT INTO redcap_external_module_settings ( external_module_id, project_id, `key`, type, value) VALUES ($em_id, {$Proj->project["project_id"]}, '$key', '$type', '$value' )";
//                            print $insert_sql;
                            $result = db_query($insert_sql);
//                            var_dump($result);
                        }
                }
                // set flag that auto enable already too place
                EM::setProjectSetting('self_service_ext_mod', $Proj->project["project_id"], 'enable_once', 1);
            } else {
//                print "enabling condition not met";
            }
        }
//        print "</pre>";
    }

    function redcap_user_rights()
    {
        /**
         * If your custom module is called "$this->PREFIX", you can hide it by adding this to your external module
         * NOTE: you have to add "redcap_user_rights" permission to your config.json
         *
         * This function detects when the editUserPopup window is opened and injects javascript in that window to HIDE the DIV where the external Module checkbox is located
         */
        print  "<script type='text/javascript'> \$( '#editUserPopup' )
                .on( \"dialogopen\",
                    function( event, ui ) {
                        \$(\"input[value='$this->PREFIX']\").parent(\"div\").hide();
                    }
                );
            </script>";
    }
}
