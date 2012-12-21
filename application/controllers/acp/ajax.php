<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	function show_realms_status()
        {
            $this->load->model('acp_ajax');
            $data['base_url'] = base_url();
            $data['realms'] = $this->acp_ajax->show_realms_status();
			
            $this->parser->parse('acp/content/realms', $data);
        }
        
        function show_status()
        {
						
            $online  = '<font color="green">Online</font>';
            $offline  = '<font color="red">Offline</font>';
						
						
            $fp = @fsockopen($this->config->item('server_ip'), $this->config->item('mysql_port'), $errno, $errstr, 1);
            $mysql_status = (!$fp) ? $offline : $online;
            $fp = @fsockopen($this->config->item('server_ip'), $this->config->item('apache_port'), $errno, $errstr, 1);
            $apache_status = (!$fp) ? $offline : $online;
            $fp = @fsockopen($this->config->item('server_ip'), $this->config->item('auth_port'), $errno, $errstr, 1);
            $auth_status = (!$fp) ? $offline : $online;
            
            $cont = '';
            $cont .= 'Auth Server '.$auth_status.'<br />
                MySQL Server '.$mysql_status.'<br />
                Apache Server '.$apache_status.'<br />';
            
            echo $cont;
        }
        
        function array_to_json( $array ){

            if( !is_array( $array ) ){
                return false;
            }

            $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
            if( $associative ){

                $construct = array();
                foreach( $array as $key => $value ){

                    // We first copy each key/value pair into a staging array,
                    // formatting each key and value properly as we go.

                    // Format the key:
                    if( is_numeric($key) ){
                        $key = "key_$key";
                    }
                    $key = "\"".addslashes($key)."\"";

                    // Format the value:
                    if( is_array( $value )){
                        $value = $this->array_to_json( $value );
                    } else if( !is_numeric( $value ) || is_string( $value ) ){
                        $value = "\"".addslashes($value)."\"";
                    }

                    // Add to staging array:
                    $construct[] = "$key: $value";
                }

                // Then we collapse the staging array into the JSON form:
                $result = "{ " . implode( ", ", $construct ) . " }";

            } else { // If the array is a vector (not associative):

                $construct = array();
                foreach( $array as $value ){

                    // Format the value:
                    if( is_array( $value )){
                        $value = $this->array_to_json( $value );
                    } else if( !is_numeric( $value ) || is_string( $value ) ){
                        $value = "'".addslashes($value)."'";
                    }

                    // Add to staging array:
                    $construct[] = $value;
                }

                // Then we collapse the staging array into the JSON form:
                $result = "[ " . implode( ", ", $construct ) . " ]";
            }

            return $result;
        }

        function show_items($realm_id = '1')
        {
            $q = strtolower(trim($_GET["term"]));
            $this->load->model('acp_ajax');
            if (!$q || strlen($q)<2) return;
            $items = $this->acp_ajax->return_items($q);

            $result = array();
            foreach ($items as $item) {
                $result[] = $item['name']."|".$item['entry']."|".$item['quality'];
			}
		
            echo json_encode($result);
        }
}