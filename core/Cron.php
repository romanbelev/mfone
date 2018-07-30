<?php
/**
 * Cron task class
 *
 *
*/


namespace core;

class Cron
{
	protected $task = array();

	public function __construct() 
	{

	}

	/**
	 * Cron add tasks
	 *
	 * @since 1.0.0
	 * @param string $name Unique task name
	 * @param string $path Class and method for run
	 * @param string $frequency Task run frequency
	*/
	public function addTask( $name, $path, $frequency ) {
		$this->task[] = array(
			'name' => $name,
			'action' => $path,
			'frequency' => $frequency
		);
	}

	public function runTask() {
		//print_r( $this->task );

		$mysqli = new \mysqli( 'localhost', 'mysql', 'mysql', 'mf' );
		//$mysqli = new \mysqli( 'localhost', 'a0139772_bwt', 'Mypassword219', 'a0139772_bwt' );

		$query = "INSERT IGNORE INTO cron (name, action, frequency, last) VALUES ";

		$dateTime = new \DateTime( 'now' );
		$date = $dateTime->format( 'Y-m-d H:i:s' );

		foreach ( $this->task as $key => $value ) {

			$name = $value[ 'name' ];
			$action = $value[ 'action' ];
			$frequency = $value[ 'frequency' ];

			$insert[] = "('$name', '$action', '$frequency', '$date')";
		}

		$mysqli->query( $query . implode( ',', $insert ) );

	
		// Cron run task
		$cronTask = $mysqli->query( "SELECT * FROM cron" );

		$timeNow = new \DateTime( 'now' );
		$timeSql = $dateTime->format( 'Y-m-d H:i:s' );

		while ( $value = $cronTask->fetch_assoc() ) {
			
			$lastTimeRun = new \DateTime( $value['last'] );
			$timeToRun = $lastTimeRun->modify( '+' . $value[ 'frequency' ] );

			//$controllerMethod = explode( '::', $value['action'] );
			//$controller = 'app\tasks\\' . $controllerMethod[0] . 'Task';
			//$method = $controllerMethod[1];

			if ( $timeToRun < $timeNow ) {
				
				$nameTask = $value['name'];
				$mysqli->query( "UPDATE cron SET last = '$timeSql' WHERE name = '$nameTask'" );

				$this->taskAction( $value[ 'action' ] );
				//$instance = new $controller;
				//$instance->$method();
			}
		}
		
	}

	public function taskAction( $route )
	{
		$controllerMethod = explode( '::', $route );

		$controller = 'app\controllers\\' . $controllerMethod[0] . 'Controller';
		$method = $controllerMethod[1];

		$instance = new $controller( $controllerMethod[0] );
		$instance->$method();
	}
		
}

