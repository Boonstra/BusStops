<?php
/**
 * ApiController
 */
class ApiController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array();
	}

	/**
	 * Lists all implemented objects within the given datetime range.
	 *
	 * A limit and offset can be set.
	 */
	public function actionList()
	{
		$criteria = new CDbCriteria();

//		$gpsLongitude = 53.13; // Central Groningen according to Wikipedia
//		$gpsLatitude  = 6.34;
//
//		$range = 6000; //25; // TODO The smallest distance was at least 5975. The database is currently incorrect, though. (17-10-2013 Stefan home PC)
//
//		// TODO Figure out whether this returns in miles or KM, probably miles.
//		echo "SELECT *, (/*3959*/ 6372.797 * acos(cos(radians($gpsLatitude)) * cos(radians(`GPS_Latitude`)) * cos(radians(`GPS_Longitude`) - radians($gpsLongitude)) + sin(radians($gpsLatitude)) * sin(radians(`GPS_Latitude`)))) AS `distance` FROM `busstops` /*HAVING `distance` < $range*/ ORDER BY `distance` ASC /*LIMIT 0, 20*/";
//
//		// This returns the distance between Groningen's central location (53.13 (lat), 6.34 (long)) according to Wikipedia and the bus stop with ID: 119552. (52.5234090000 (lat), 7.2577900000 (long))
//		function distance($lat1, $lng1, $lat2, $lng2, $miles = true)
//		{
//			$pi80 = M_PI / 180;
//			$lat1 *= $pi80;
//			$lng1 *= $pi80;
//			$lat2 *= $pi80;
//			$lng2 *= $pi80;
//
//			$r = 6372.797; // mean radius of Earth in km
//			$dlat = $lat2 - $lat1;
//			$dlng = $lng2 - $lng1;
//			$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
//			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
//			$km = $r * $c;
//
//			return ($miles ? ($km * 0.621371192) : $km);
//		}
//
//		var_dump(distance(53.13, 6.34, 52.5234090000, 7.2577900000, false));
//		var_dump(distance(53.2116, 6.5658, 52.5234090000, 7.2577900000, false));
//
//		return;

		$criteria->offset = 0;
		$criteria->limit  = 20;

//		if (isset($_REQUEST['offset']) &&
//			is_numeric($_REQUEST['offset']) &&
//			$_REQUEST['offset'] >= 0)
//		{
//			$criteria->offset = $_REQUEST['offset'];
//		}
//
//		if (isset($_REQUEST['limit']) &&
//			is_numeric($_REQUEST['limit']) &&
//			$_REQUEST['limit'] >= 0)
//		{
//			$criteria->limit = $_REQUEST['limit'];
//		}
//
//		$dateFrom = $dateTo = "";
//
//		if (isset($_REQUEST['dateFrom']) &&
//			strlen($_REQUEST['dateFrom']) > 0)
//		{
//			$dateFrom = $_REQUEST['dateFrom'];
//		}
//
//		if (isset($_REQUEST['dateTo']) &&
//			strlen($_REQUEST['dateTo']) > 0)
//		{
//			$dateTo = $_REQUEST['dateTo'];
//		}
//
//		if (!empty($dateFrom) &&
//			!empty($dateTo))
//		{
//			$criteria->addBetweenCondition('datetime', $dateFrom, $dateTo);
//		}
//		else if (!empty($dateFrom))
//		{
//			$criteria->addBetweenCondition('datetime', $dateFrom, date('Y-m-d h:i:s'));
//		}
//		else if (!empty($dateTo))
//		{
//			$criteria->addBetweenCondition('datetime', '1900-01-01 00:00:00', $dateTo);
//		}

		switch(strtolower($_GET['model']))
		{
			case 'busstop':
				$models = BusStop::model()->findAll($criteria);
				break;

			default:
				$this->_sendResponse(501, CJSON::encode(array("message" => "failed", "error" => "The list action is not implemented for this model")));
				Yii::app()->end();
		}

		$rows = array();

		if (count($models) > 0)
		{
			foreach ($models as $model)
			{
				$rows[] = $model->attributes;
			}
		}

		$this->_sendResponse(200, CJSON::encode(array("message" => "success", "measurements" => $rows)));
	}

	/* Shows a single item
	 *
	 * @access public
	 * @return void
	 */
	public function actionView()
	{
		$this->_sendResponse(501, CJSON::encode(array("message" => "failed", "error" => "Not implemented yet")));
	}

	/**
	 * Sends the API response
	 *
	 * @param int $status
	 * @param string $body
	 * @param string $content_type
	 * @access private
	 * @return void
	 */
	private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
	{
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		// set the status
		header($status_header);
		// set the content type
		header('Content-type: ' . $content_type);

		// pages with body are easy
		if($body != '')
		{
			// send the body
			echo $body;
			exit;
		}
		// we need to create the body if none is passed
		else
		{
			// create some body messages
			$message = '';

			// this is purely optional, but makes the pages a little nicer to read
			// for your users.  Since you won't likely send a lot of different status codes,
			// this also shouldn't be too ponderous to maintain
			switch($status)
			{
				case 401:
					$message = 'You must be authorized to view this page.';
					break;
				case 404:
					$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
					break;
				case 500:
					$message = 'The server encountered an error processing your request.';
					break;
				case 501:
					$message = 'The requested method is not implemented.';
					break;
			}

			// servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
			$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

			// this should be templatized in a real-world solution
			$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                        <html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                                <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
                            </head>
                            <body>
                                <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
                                <p>' . $message . '</p>
                                <hr />
                                <address>' . $signature . '</address>
                            </body>
                        </html>';

			echo $body;
			exit;
		}
	}

	/**
	 * Gets the message for a status code
	 *
	 * @param mixed $status
	 * @access private
	 * @return string
	 */
	private function _getStatusCodeMessage($status)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);

		return (isset($codes[$status])) ? $codes[$status] : '';
	}

	/**
	 * This method takes a username and password (or gets them from $_REQUEST when empty) to check whether or not they
	 * match with a User record from the database.
	 *
	 * Returns an array with as fist value an integer indicating whether the user is 0: Valid, 1: Invalid, or 2: Blocked.
	 * The second value in the returned array is the User model instance.
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return array [int 0 | 1 | 2, User user]
	 */
	private function _getUserAuthentication($username = "", $password = "")
	{
		if (strlen($username) <= 0 &&
			isset($_REQUEST['username']) &&
			strlen($_REQUEST['username']) > 0)
		{
			$username = $_REQUEST['username'];
		}
		else
		{
			return false;
		}

		if (strlen($password) <= 0 &&
			isset($_REQUEST['password']) &&
			strlen($_REQUEST['password']) > 0)
		{
			$password = $_REQUEST['password'];
		}
		else
		{
			return false;
		}

		$user = User::model()->findByAttributes(array('username' => $username));

		// Check if user object was found
		if ($user === null)
		{
			return array(1, $user);
		}
		// Check if account is available
		else if (!$user->accountAvailable($user->username))
		{
			return array(2, $user);
		}
		// Check if passwords match
		else if (!CPasswordHelper::verifyPassword($password, $user->password))
		{
			// Store failed login
			$failedLogin = new FailedLogins();

			$failedLogin->ipadress = Yii::app()->getRequest()->getUserHostAddress();
			$failedLogin->datetime = date("Y-m-d H:i:s");
			$failedLogin->user_id  = $user->id;

			$failedLogin->save();

			return array(1, $user);
		}
		// Successful login
		else
		{
			return array(0, $user);
		}
	}

	/**
	 * Outputs a JSON response to the page when authentication has failed,
	 * otherwise returns a user object.
	 *
	 * Uses $this->_getUserAuthentication
	 *
	 * @return $user
	 */
	private function _checkUserAuthentication()
	{
		list($resultCode, $user) = $this->_getUserAuthentication();

		// Successful
		if ($resultCode === 0)
		{
			return $user;
		}
		// Blocked
		else if ($resultCode === 2)
		{
			$this->_sendResponse(200, CJSON::encode(array('message' => 'blocked')));

			return null;
		}

		$this->_sendResponse(200, CJSON::encode(array('message' => 'failed')));

		return null;
	}
}
