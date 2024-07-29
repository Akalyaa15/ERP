<?php

class Terms_conditions_templates_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'terms_conditions_templates';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $terms_conditions_templates_table = $this->db->dbprefix('terms_conditions_templates');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $terms_conditions_templates_table.id=$id";
        }

        $sql = "SELECT $terms_conditions_templates_table.*
        FROM $terms_conditions_templates_table
        WHERE $terms_conditions_templates_table.deleted=0 $where";
        return $this->db->query($sql);
    }
    function get_default() {
        $terms_conditions_templates_table = $this->db->dbprefix('terms_conditions_templates');

        $sql = "SELECT $terms_conditions_templates_table.*
        FROM $terms_conditions_templates_table
        WHERE $terms_conditions_templates_table.deleted=0 AND $terms_conditions_templates_table.is_default=1";
        return $this->db->query($sql);
    }
    function set_zero() {
        $terms_conditions_templates_table = $this->db->dbprefix('terms_conditions_templates');

        $sql = "UPDATE $terms_conditions_templates_table SET is_default = 0";
        return $this->db->query($sql);
    }
    function get_final_template($template_name = "") {
        $terms_conditions_templates_table = $this->db->dbprefix('terms_conditions_templates');

        $sql = "SELECT $terms_conditions_templates_table.default_message, $terms_conditions_templates_table.custom_message, $terms_conditions_templates_table.email_subject, 
            signature_template.custom_message AS signature_custom_message, signature_template.default_message AS signature_default_message
        FROM $terms_conditions_templates_table
        LEFT JOIN $terms_conditions_templates_table AS signature_template ON signature_template.template_name='signature'
        WHERE $terms_conditions_templates_table.deleted=0 AND $terms_conditions_templates_table.template_name='$template_name'";
        $result = $this->db->query($sql)->row();

        $info = new stdClass();
        $info->subject = $result->email_subject;
        $info->message = $result->custom_message ? $result->custom_message : $result->default_message;
        $info->signature = $result->signature_custom_message ? $result->signature_custom_message : $result->signature_default_message;

        return $info;
    }

}
