<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;

/**
 * Cron Controller
 *
 *
 * @method \App\Model\Entity\Cron[] paginate($object = null, array $settings = [])
 */
class CronController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function initialize()
    {
    		parent::initialize();
        $this->Auth->allow();
        $this->loadModel('Video');
        $this->loadModel('Sound');
        $this->loadModel('Hashtag');
        $this->loadModel('HashtagVideo');
        $this->loadModel('User');
        $this->loadModel('Notification');
        $this->loadModel('Follower');
        $this->loadModel('PushNotification');
				$this->loadModel('TopicVideo');
    }


    public function index()
    {
    		$this->loadModel('Video');
        $this->loadModel('Sound');
        $this->loadModel('Hashtag');
        $this->loadModel('HashtagVideo');
        $this->loadModel('User');
        $this->loadModel('Notification');
        $this->loadModel('Follower');
        $this->loadModel('PushNotification');
				$this->loadModel('TopicVideo');

        $videos = $this->Video->find('all',['conditions'=>['status' => '0']]);
        foreach ($videos as $key => $value) {
        		$this->Video->id = $value['id'];
            $this->Video->saveField('status', 1);

						$sound_details = $this->Sound->getDetails($value['sound_id']);
						$video_details = $this->Video->getDetails($video_id);
            $result_video = Regular::local_video_upload($value['user_id'], $value['video'], $sound_details, $video_details, 0);

            $video_url = $result_video['video'];
          	$gif_url = $result_video['gif'];
            $thum_url = $result_video['thum'];

            $video_save['sound_id'] = $value['sound_id'];
						if (count($result_video) > 0) {
								$video_duration = Utility::getDurationOfVideoFile($result_video['video']);
								if (strlen($result_video['audio']) > 2) {

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
								$video_save['description'] = $description;
								$video_save['privacy_type'] = $privacy_type;
								$video_save['allow_comments'] = $allow_comments;
								$video_save['allow_duet'] = $allow_duet;
								$video_save['user_id'] = $user_id;
								$video_save['interest_id'] = $interest_id;
								$video_save['created'] = $created;
								$video_save['main_video_id'] = $main_video_id;
								$video_save['country_id'] = $country_id;
								$video_save['status'] = 2;

								$this->Video->id = $value['id'];

								if (!$this->Video->save($video_save)) {
										die();
								}

								/**************hashtag save******************/

								if (count($data_hashtag) > 0) {
										foreach ($data_hashtag as $key => $value) {

												$name = $value['name'];

												$if_hashtag_exist = $this->Hashtag->ifExist($name);

												if (count($if_hashtag_exist) < 1) {
														$hashtag['name'] = $name;
														$hashtag['lang_id'] = $lang_id;
														$this->Hashtag->save($hashtag);
														$hashtag_id = $this->Hashtag->getInsertID();
														$this->Hashtag->clear();
												} else {
														$hashtag_id = $if_hashtag_exist['Hashtag']['id'];
												}
												$hashtag_video[$key]['hashtag_id'] = $hashtag_id;
												$hashtag_video[$key]['video_id'] = $video_id;
												//$this->HashtagVideo->save($hashtag_video);
										}
										/*if (count($hashtag_video) > 0) {
												$this->HashtagVideo->saveAll($hashtag_video);
										}*/
								}

								$hashtag_video[$topic_id]['hashtag_id'] = $topic_id;
								$hashtag_video[$topic_id]['video_id'] = $video_id;
								$this->HashtagVideo->saveAll($hashtag_video);

								/*************************end hashtag save ********************/

								/**************pushnotification to tagged users******************/


								if (count($data_users) > 0) {
										foreach ($data_users as $key => $value) {
												$user_id = $value['user_id'];

												$tagged_userDetails = $this->User->getUserDetailsFromID($user_id);
												$msg = $video_userDetails['User']['username'] . " has tagged you in a video";

												if (strlen($tagged_userDetails['User']['device_token']) > 8) {
														$notification['to'] = $tagged_userDetails['User']['device_token'];

														$notification['notification']['title'] = $msg;
														$notification['notification']['body'] = "";
														$notification['notification']['badge'] = "1";
														$notification['notification']['sound'] = "default";
														$notification['notification']['icon'] = "";
														$notification['notification']['type'] = "video_tag";
														$notification['data']['title'] = '';
														$notification['data']['body'] = $msg;
														$notification['data']['icon'] = "";
														$notification['data']['badge'] = "1";
														$notification['data']['sound'] = "default";
														$notification['data']['type'] = "video_tag";
														$notification['notification']['receiver_id'] =  $tagged_userDetails['User']['id'];
														$notification['data']['receiver_id'] = $tagged_userDetails['User']['id'];

														$if_exist = $this->PushNotification->getDetails($tagged_userDetails['User']['id']);

														if (count($if_exist) > 0) {

																$video_updates = $if_exist['PushNotification']['video_updates'];
																if ($video_updates > 0) {
																		Utility::sendPushNotificationToMobileDevice(json_encode($notification));
																}
														}


														$notification_data['sender_id'] = $video_userDetails['User']['id'];
														$notification_data['receiver_id'] = $tagged_userDetails['User']['id'];
														$notification_data['type'] = "video_tag";
														$notification_data['video_id'] = $video_id;

														$notification_data['string'] = $msg;
														$notification_data['created'] = $created;

														$this->Notification->save($notification_data);
												}
										}
								}
								/*************************end hashtag save********************/

								/**************pushnotification to tagged users******************/
								$all_followers = $this->Follower->getUserFollowersWithoutLimit($user_id);
								if (count($all_followers) > 0) {
										foreach ($all_followers as $key => $value) {
												$user_id = $value['FollowerList']['id'];
												$device_token = $value['FollowerList']['device_token'];

												$msg = $video_userDetails['User']['username'] . " has posted a a video";

												if (strlen($device_token) > 8) {
														$notification['to'] = $device_token;

														$notification['notification']['title'] = $msg;
														$notification['notification']['body'] = "";
														$notification['notification']['badge'] = "1";
														$notification['notification']['sound'] = "default";
														$notification['notification']['icon'] = "";
														$notification['notification']['type'] = "video_new_post";
														$notification['data']['title'] = '';
														$notification['data']['body'] = $msg;
														$notification['data']['icon'] = "";
														$notification['data']['badge'] = "1";
														$notification['data']['sound'] = "default";
														$notification['data']['type'] = "video_new_post";
														$notification['notification']['receiver_id'] =  $value['FollowerList']['id'];
														$notification['data']['receiver_id'] = $value['FollowerList']['id'];

														$if_exist = $this->PushNotification->getDetails($user_id);

														if (count($if_exist) > 0) {
																$video_updates = $if_exist['PushNotification']['video_updates'];
																if ($video_updates > 0) {
																		Utility::sendPushNotificationToMobileDevice(json_encode($notification));
																}
														}

														$notification_data['sender_id'] = $video_userDetails['User']['id'];
														$notification_data['receiver_id'] = $user_id;
														$notification_data['type'] = "video_updates";
														$notification_data['video_id'] = $video_id;

														$notification_data['string'] = $msg;
														$notification_data['created'] = $created;

														$this->Notification->save($notification_data);
												}
										}
								}
								/*************************end pushnotification to tagged users********************/
        		}
        }
        //pr($value);die;
        $this->set(compact('cron'));
        $this->set('_serialize', ['cron']);
    }
}
