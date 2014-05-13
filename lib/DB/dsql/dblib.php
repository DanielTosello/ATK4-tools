<?php
/**
 * Agile Toolkit 4 dblib dsql connector. 
 * 
 * Designed to use a locally configured dblib connector, unix only.
 * Requires a dblib/FreeTDS connector be configured and available on the host system 
 * with a valid MSSQL database on the other end.  
 * 
 * This file should be placed in the /lib/DB/dsql/ folder in your ATK4 project. 
 * Standard DB functions should work... though testing has not yet been exhaustive.
 * 
 * At this stage target database cannot have spaces or slashes due to other background 
 * stuff in the framework. Correcting this is on the TODO list.   
 *  
 *  Author: Daniel Tosello (tosello.daniel@gmail.com)
 *  License: GNU AFFERO GENERAL PUBLIC LICENSE - please see http://www.gnu.org/licenses/
 */




class DB_dsql_dblib extends DB_dsql {
	
        public $bt="";
                
        function init(){
                parent::init();

        }
        
        /**
         * MSSQL likes its limits in a different way to other SQL languages.
         * 
         * MySQL: '... order by id limit n,n'
         * MSSQL: '... order by id offset n rows fetch next n rows only'
         * 
         * @return string
         */

        function render_limit(){
                if($this->args['limit']){
                        return 'order by id offset '.
                                (int)$this->args['limit']['shift'].
                                ' rows fetch next '.
                                (int)$this->args['limit']['cnt'].
                                ' rows only';
                }
        }

        
        /**
         * MSSQL don't care for your fancy having. Redirecting to where.
         * 
         * @param unknown $field
         * @param string $cond
         * @param string $value
         */
        
        function having($field, $cond = UNDEFINED, $value = UNDEFINED){
                return $this->where($field, $cond, $value);
        }

        /**
         * Overridden backtick method. Encapsulates strings using the MSSQL [string] method.
         * 
         * @param String $s
         * @return multitype:Ambigous <string, unknown, multitype:NULL > |unknown|string
         */
        
        function bt($s)
        {
        	if (is_array($s)) {
        		$out=array();
        		foreach ($s as $ss) {
        			$out[]=$this->bt($ss);
        		}
        		return $out;
        	}
        
        	if (is_object($s)
        	|| $s==='*'
        			|| strpos($s, '.')!==false
        			|| strpos($s, '(')!==false
        	) {
        		return $s;
        	}
        
        	return '['.$s.']';
        }
}
?>

