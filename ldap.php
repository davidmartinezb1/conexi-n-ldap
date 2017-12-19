 
<?php

print "<pre>";
 /*$adServer = "ldap://10.10.0.2:389";
	
    $ldap = ldap_connect($adServer);
    $username = 'mantis reportes';
    $password = 'M17!!*Hsystem';

    $ldaprdn = 'mantis reportes';

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($ldap, $ldaprdn, $password);


    if ($bind) {
        $filter="(cn=HMantis)";
        $result = ldap_search($ldap,"dc=elheraldoltda,dc=local",$filter);
         print $result;
        ldap_sort($ldap,$result,"sn*");
        $info = ldap_get_entries($ldap, $result);
       // print_r($info);
        print "conexión";
        for ($i=0; $i<$info["count"]; $i++)
        {
            if($info['count'] > 1)
                break;
            echo "<p>You are accessing <strong> ". $info[$i]["sAMAccountName"][0] .", " . $info[$i]["givenname"][0] ."</strong><br /> (" . $info[$i]["sAMAccountName"][0] .")</p>\n";
            echo '<pre>';
            var_dump($info);
            echo '</pre>';
            $userDn = $info[$i]["distinguishedname"][0]; 
        }
        @ldap_close($ldap);
    } else {
        $msg = "Invalid email address / password";
        echo $msg;
    }
*/

$message="";
$ldap_username = 'usuario';
$ldap_password = 'contraseña';
$ip="";// ip del ldap
//$ldap_username= 'chat';
//$ldap_password = 'H16*-20%$37r';

//$ldap_username = 'mantis reportes';
$ldap_connection = ldap_connect("ldap://".$ip);
if (FALSE === $ldap_connection){
    // Uh-oh, something is wrong...
}

// We have to set this option for the version of Active Directory we are using.
ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.

if (TRUE === ldap_bind($ldap_connection, $ldap_username, $ldap_password)){
    $ldap_base_dn = 'dc=elheraldoltda,dc=local';
    $search_filter = '(&(objectCategory=person)(sAMAccountName=*))';
    $attributes = array();
    $attributes[] = 'givenname';
    $attributes[] = 'mail';
    $attributes[] = 'sAMAccountName';
    $attributes[] = 'sn';
    $result = ldap_search($ldap_connection, $ldap_base_dn, $search_filter, $attributes);
    if (FALSE !== $result){
        $entries = ldap_get_entries($ldap_connection, $result);
        print_r($entries);
        for ($x=0; $x<$entries['count']; $x++){
            if (!empty($entries[$x]['givenname'][0]) &&
                 !empty($entries[$x]['mail'][0]) &&
                 !empty($entries[$x]['sAMAccountName'][0]) &&
                 !empty($entries[$x]['sn'][0]) &&
                 'Shop' !== $entries[$x]['sn'][0] &&
                 'Account' !== $entries[$x]['sn'][0]){
                 $ad_users[strtoupper(trim($entries[$x]['sAMAccountName'][0]))] = array('email' => strtolower(trim($entries[$x]['mail'][0])),'first_name' => trim($entries[$x]['givenname'][0]),'last_name' => trim($entries[$x]['sn'][0]));
                 //($ad_users[strtoupper(trim($entries[$x]['sAMAccountName'][0]))]);userpassword
            }
        }
    }
    ldap_unbind($ldap_connection); // Clean up after ourselves.
}

print $message .= "Retrieved ". count($ad_users) ." Active Directory users\n";
//print_r($ad_users);
?>