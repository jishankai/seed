<?php
class AdsController extends Controller
{    
    public function actionAppdriverAds() {
		$identifier = $this->playerId;
		$digest = hash('sha256', $identifier.';'.MEDIA_ID.';'.SITE_KEY);
		$uri = '/3.0.'.SITE_ID.'i?identifier='.$identifier.'&media_id='.MEDIA_ID.'&digest='.$digest;
		$url = APP_DRIVER_SANDBOX ? 'http://sandbox.appdriver.jp'.$uri : 'http://appdriver.jp'.$uri;	

		echo '<script>window.location.href = "native://action=loginSns&url='.base64_encode($url).'";</script>';
	}
}
?>
