<?php
/**
 * @author blaza 2010-04
 */
 
class PackageControl {
	private static $services = array();	
	//gdy usługa z ograniczeniem ilościowym bez ograniczeń
	protected static $_packageMaxResources = 1000;
	
	private static function init($packageId)
	{		
		if(!isset(self::$services[$packageId]))			
			self::setPackageServices($packageId);			
	}
	
	public static function getValue($packageId, $serviceRole)//$serviceName 
	{			
		self::init($packageId);
		
		if(!isset(self::$services[$packageId][$serviceRole]))
			return true;
		
		return self::$services[$packageId][$serviceRole];		
	}
	
	protected static function setPackageServices($packageId)
	{        		
		$services = PackageService::model()->findAll();		
		$packageServices = PackageServiceMN::model()->findAll(
	    		'package_id=:package_id',
	    		array(':package_id'=>$packageId)
	    	);		
		
		$servicesControl = array();
		for($i=0; $i<count($services);++$i) {
			$serviceExists = false;			
			$service = $services[$i];
			$serviceInPackage = NULL;
			$j = 0;
			while(($j<count($packageServices)) && !$serviceInPackage)
			{
				if($service['id'] == $packageServices[$j]['service_id'])
					$serviceInPackage = $packageServices[$j];
				++$j;
			}
			if($serviceInPackage) {
				if($service['value_type'] == 0)
					//wartosci logiczne tak,nie
					$servicesControl[$service['role']] = 1;
				else {
					if(!$serviceInPackage['threshold'])
						$servicesControl[$service['role']] = self::$_packageMaxResources;
					else		
						$servicesControl[$service['role']] = $serviceInPackage['threshold'];
				}	
			} else {
				//if($service['value_type'])
				$servicesControl[$service['role']] =  0;
			}	
				
		}		
		self::$services[$packageId] = $servicesControl;		
	}
}
?>