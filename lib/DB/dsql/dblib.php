<?php
/**
 * Agile Toolkit 4 bdlib dsql connector. 
 * 
 * Designed to use a locally configured dblib connector, unix only.
 *  
 */



class DB_dsql_dblib extends DB_dsql {
	
        public $bt="";
        
        
        function init(){
                parent::init();

        }

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

