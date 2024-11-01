<?php
/** 
 * Function file for Video Blogger plugin 
 * 
 * @link by WebTechGlobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 * 
 * @since 0.0.1
 */
 
 
/*   Column Array Example Returned From "mysql_query("SHOW COLUMNS FROM..."
        
          array(6) {
            [0]=>
            string(5) "row_id"
            [1]=>
            string(7) "int(11)"
            [2]=>
            string(2) "NO"
            [3]=>
            string(3) "PRI"
            [4]=>
            NULL
            [5]=>
            string(14) "auto_increment"
          }
                  
    +------------+----------+------+-----+---------+----------------+
    | Field      | Type     | Null | Key | Default | Extra          |
    +------------+----------+------+-----+---------+----------------+
    | Id         | int(11)  | NO   | PRI | NULL    | auto_increment |
    | Name       | char(35) | NO   |     |         |                |
    | Country    | char(3)  | NO   | UNI |         |                |
    | District   | char(20) | YES  | MUL |         |                |
    | Population | int(11)  | NO   |     | 0       |                |
    +------------+----------+------+-----+---------+----------------+            
*/
   
global $wpdb;   
$wtgvb_tables_array =  array();
##################################################################################
#                               wtgvblog                                        #
##################################################################################        
$wtgvb_tables_array['tables']['wtgvblog']['name'] = $wpdb->prefix . 'wtgvblog';
$wtgvb_tables_array['tables']['wtgvblog']['required'] = false;// required for all installations or not (boolean)
$wtgvb_tables_array['tables']['wtgvblog']['usercreated'] = false;// if the table is created as a result of user actions rather than core installation put true
$wtgvb_tables_array['tables']['wtgvblog']['version'] = '0.0.1';// used to force updates based on version alone rather than individual differences
$wtgvb_tables_array['tables']['wtgvblog']['primarykey'] = 'row_id';
$wtgvb_tables_array['tables']['wtgvblog']['uniquekey'] = 'row_id';
// wtgvblog - row_id
$wtgvb_tables_array['tables']['wtgvblog']['columns']['row_id']['type'] = 'bigint(20)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['row_id']['null'] = 'NOT NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['row_id']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['row_id']['default'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['row_id']['extra'] = 'AUTO_INCREMENT';
// wtgvblog - outcome
$wtgvb_tables_array['tables']['wtgvblog']['columns']['outcome']['type'] = 'tinyint(1)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['outcome']['null'] = 'NOT NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['outcome']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['outcome']['default'] = '1';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['outcome']['extra'] = '';
// wtgvblog - timestamp
$wtgvb_tables_array['tables']['wtgvblog']['columns']['timestamp']['type'] = 'timestamp';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['timestamp']['null'] = 'NOT NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['timestamp']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['timestamp']['default'] = 'CURRENT_TIMESTAMP';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['timestamp']['extra'] = '';
// wtgvblog - line
$wtgvb_tables_array['tables']['wtgvblog']['columns']['line']['type'] = 'int(11)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['line']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['line']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['line']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['line']['extra'] = '';
// wtgvblog - file
$wtgvb_tables_array['tables']['wtgvblog']['columns']['file']['type'] = 'varchar(250)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['file']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['file']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['file']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['file']['extra'] = '';
// wtgvblog - function
$wtgvb_tables_array['tables']['wtgvblog']['columns']['function']['type'] = 'varchar(250)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['function']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['function']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['function']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['function']['extra'] = '';
// wtgvblog - sqlresult
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlresult']['type'] = 'blob';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlresult']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlresult']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlresult']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlresult']['extra'] = '';
// wtgvblog - sqlquery
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlquery']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlquery']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlquery']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlquery']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlquery']['extra'] = '';
// wtgvblog - sqlerror
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlerror']['type'] = 'mediumtext';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlerror']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlerror']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlerror']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['sqlerror']['extra'] = '';
// wtgvblog - wordpresserror
$wtgvb_tables_array['tables']['wtgvblog']['columns']['wordpresserror']['type'] = 'mediumtext';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['wordpresserror']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['wordpresserror']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['wordpresserror']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['wordpresserror']['extra'] = '';
// wtgvblog - screenshoturl
$wtgvb_tables_array['tables']['wtgvblog']['columns']['screenshoturl']['type'] = 'varchar(500)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['screenshoturl']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['screenshoturl']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['screenshoturl']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['screenshoturl']['extra'] = '';
// wtgvblog - userscomment
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userscomment']['type'] = 'mediumtext';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userscomment']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userscomment']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userscomment']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userscomment']['extra'] = '';
// wtgvblog - page
$wtgvb_tables_array['tables']['wtgvblog']['columns']['page']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['page']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['page']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['page']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['page']['extra'] = '';
// wtgvblog - version
$wtgvb_tables_array['tables']['wtgvblog']['columns']['version']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['version']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['version']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['version']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['version']['extra'] = '';
// wtgvblog - panelid
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelid']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelid']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelid']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelid']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelid']['extra'] = '';
// wtgvblog - panelname
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelname']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelname']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelname']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelname']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['panelname']['extra'] = '';
// wtgvblog - tabscreenid
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenid']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenid']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenid']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenid']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenid']['extra'] = '';
// wtgvblog - tabscreenname
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenname']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenname']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenname']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenname']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['tabscreenname']['extra'] = '';
// wtgvblog - dump
$wtgvb_tables_array['tables']['wtgvblog']['columns']['dump']['type'] = 'longblob';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['dump']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['dump']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['dump']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['dump']['extra'] = '';
// wtgvblog - ipaddress
$wtgvb_tables_array['tables']['wtgvblog']['columns']['ipaddress']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['ipaddress']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['ipaddress']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['ipaddress']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['ipaddress']['extra'] = '';
// wtgvblog - userid
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userid']['type'] = 'int(11)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userid']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userid']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userid']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['userid']['extra'] = '';
// wtgvblog - comment
$wtgvb_tables_array['tables']['wtgvblog']['columns']['comment']['type'] = 'mediumtext';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['comment']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['comment']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['comment']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['comment']['extra'] = '';
// wtgvblog - type
$wtgvb_tables_array['tables']['wtgvblog']['columns']['type']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['type']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['type']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['type']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['type']['extra'] = '';
// wtgvblog - category
$wtgvb_tables_array['tables']['wtgvblog']['columns']['category']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['category']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['category']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['category']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['category']['extra'] = '';
// wtgvblog - action
$wtgvb_tables_array['tables']['wtgvblog']['columns']['action']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['action']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['action']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['action']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['action']['extra'] = '';
// wtgvblog - priority
$wtgvb_tables_array['tables']['wtgvblog']['columns']['priority']['type'] = 'varchar(45)';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['priority']['null'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['priority']['key'] = '';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['priority']['default'] = 'NULL';
$wtgvb_tables_array['tables']['wtgvblog']['columns']['priority']['extra'] = '';
?>