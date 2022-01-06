<?php

class ProcessShell extends Shell
{
		public $uses = array('Video', 'Sound', 'Hashtag', 'HashtagVideo', 'User', 'Notification', 'Follower', 'PushNotification');

    public function main()
    {
        $videos = $this->Video->find('all',['conditions'=>['status' => '0']]);
        foreach ($videos as $key => $value) {
        		$this->Video->id = $value['Video']['id'];
            $this->Video->saveField('status', 1);

						$sound_details = $this->Sound->getDetails($value['Video']['sound_id']);
						//$video_details = $this->Video->getDetails($value['Video']['id']);
						$video_details = array();
            $result_video = Regular::local_video_upload($value['Video']['user_id'], $value['Video']['video'], $sound_details, $video_details, 0);

            $video_url = $result_video['video'];
          	$gif_url = $result_video['gif'];
            $thum_url = $result_video['thum'];

            $video_save['sound_id'] = $value['Video']['sound_id'];
						if (count($result_video) > 0) {
								$video_duration = Utility::getDurationOfVideoFile($result_video['video']);
								if (strlen($result_video['audio']) > 2) {

										$video_userDetails = $this->User->getUserDetailsFromID($value['Video']['user_id']);

										$audio_url = Utility::getCloudFrontUrl($result_video['audio'], "/audio");

										$duration = Utility::getDurationofAudioFile($result_video['audio']);
										$sound_date['audio'] = $audio_url;
										$sound_date['duration'] = $duration;
										$sound_date['thum'] = $video_userDetails['User']['profile_pic'];
										$sound_date['name'] = "original sound - " . $video_userDetails['User']['username'];
										$sound_date['uploaded_by'] = "user";

										$this->Sound->save($sound_date);
										$sound_id = $this->Sound->getInsertID();
										$video_save['sound_id'] = $sound_id;
								}

								//$filepath_thumb = Utility::multipartFileUpload($user_id, 'thumb',$type);

								$video_save['gif'] = $gif_url;
								$video_save['duration'] = $video_duration;
								$video_save['video'] = $video_url;
								$video_save['lang_id'] = $lang_id;
								$video_save['thum'] = $thum_url;
								$video_save['status'] = 2;

								$this->Video->id = $value['Video']['id'];

								if (!$this->Video->save($video_save)) {
										die();
								}
        		}
        }
    }
}
