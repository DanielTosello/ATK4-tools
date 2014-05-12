<?php

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

        function having($field, $cond = UNDEFINED, $value = UNDEFINED){
                return $this->where($field, $cond, $value);
        }

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

