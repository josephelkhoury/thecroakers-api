<?php

class Video extends AppModel
{

    public $useTable = 'video';

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),

        'Sound' => array(
            'className' => 'Sound',
            'foreignKey' => 'sound_id',
        ),
				'Country' => array(
	    			'className' => 'Country',
	    			'foreignKey' => 'country_id'
				),
    );

    public $hasMany = array(
        'VideoComment' => array(
            'className' => 'VideoComment',
            'foreignKey' => 'video_id',
            'dependent' =>true
        ),

        'VideoFavourite' => array(
            'className' => 'VideoFavourite',
            'foreignKey' => 'video_id',
            'dependent' =>true
        ),

        'VideoLike' => array(
            'className' => 'VideoLike',
            'foreignKey' => 'video_id',
            'dependent' =>true
        ),

        'VideoWatch' => array(
            'className' => 'VideoWatch',
            'foreignKey' => 'video_id',
            'dependent' =>true
        ),
				'HashtagVideo' => array(
						'className' => 'HashtagVideo',
						'foreignKey' => 'video_id',
						'dependent' => true
				),
    );

    public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['Video']) && isset($val['Video']['user_id'])) {
				/*$user = $this->User->getUserDetailsFromID($val['Video']['user_id']);
				if ($user['User']['role'] == "publisher") {
					$results[$key]['Video']['allow_likes'] = "false";
					$results[$key]['Video']['allow_comments'] = "false";
					$results[$key]['Video']['allow_replies'] = "1";
				} else
					$results[$key]['Video']['allow_replies'] = "0";*/

				if (isset($val['Video']['country_id']) && $val['Video']['country_id'] == "0") {
					$results[$key]['Video']['Country'] = [];
					$results[$key]['Video']['Country']['id'] = 0;
					$results[$key]['Video']['Country']['name'] = "Worldwide";
					$results[$key]['Country'] = [];
					$results[$key]['Country']['id'] = 0;
					$results[$key]['Country']['name'] = "Worldwide";
				}
			}
		}
		return $results;
    }

    public function getDetails($id)
    {
    	$this->Behaviors->attach('Containable');
        return $this->find('first', array(
            'conditions' => array(
                'Video.id'=> $id,
            ),

            'contain' => array('User.PrivacySetting','User.PushNotification','Sound','VideoComment.User', 'HashtagVideo.Hashtag', 'Country'),
        ));
    }

    public function checkDuplicateold($data){
        return $this->find('count', array(
            'conditions' => array(
                'Video.description' => $data['description'],
                'Video.created' => $data['created'],
                'Video.fb_id' => $data['fb_id'],
                'Video.view' => $data['view'],
            ),
            'recursive' => -1
        ));
    }

    public function getDetailsAgainstOldVideoID($old_video_id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'Video.old_video_id'=> $old_video_id,
            ),
            'recursive'=>-1
        ));
    }

    public function getSearchResults($keyword,$starting_point,$user_id){
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'conditions' => array(
                'Video.description Like' => "$keyword%",
                'Video.user_id !=' => $user_id,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'HashtagVideo.Hashtag', 'Country'),
            'limit'=>10,
            'offset' => $starting_point*10,
            'recursive' => 0
        ));
    }

    public function checkDuplicate($data){
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'conditions' => array(
                'Video.description' => $data['description'],
                'Video.user_id=' => $data['user_id'],
                'Video.video' => $data['video'],
                'Video.thum' => $data['thum'],
                'Video.gif' => $data['gif'],
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'HashtagVideo.Hashtag', 'Country'),
            'limit'=>10,
            'recursive' => 0
        ));

    }
    public function getUserPublicVideos($user_id,$starting_point)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'conditions' => array(
                'Video.user_id'=> $user_id,
                'Video.privacy_type'=> "public",
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting', 'User.PushNotification', 'User.Country', 'Sound', 'HashtagVideo.Hashtag', 'Country'),
            'limit' => APP_RECORDS_PER_PAGE,
            'offset' => $starting_point*APP_RECORDS_PER_PAGE,
            'order' => 'Video.id DESC'
        ));
    }

    public function getUserPublicVideosAdmin($user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'conditions' => array(
                'Video.user_id'=> $user_id,
                'Video.privacy_type'=> "public",
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'HashtagVideo.Hashtag', 'Country'),
            'order' => 'Video.id DESC'
        ));
    }

    public function getUserPrivateVideosAdmin($user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'conditions' => array(
                'Video.user_id'=> $user_id,
                'Video.privacy_type'=> "private",
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'HashtagVideo.Hashtag', 'Country'),
            'order' => 'Video.id DESC'
        ));
    }

    public function getUserVideos($user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'conditions' => array(
                'Video.user_id'=> $user_id,
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'HashtagVideo.Hashtag', 'Country'),
            'order' => 'Video.id DESC'
        ));
    }

    public function getAllVideos()
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'order' => 'Video.id DESC',
            'contain' => array('User','Sound', 'HashtagVideo.Hashtag', 'Country'),
        ));
    }

    public function getUserPrivateVideos($user_id,$starting_point)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'conditions' => array(
                'Video.user_id'=> $user_id,
                'Video.privacy_type'=> "private",
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting', 'User.PushNotification', 'User.Country', 'Sound', 'HashtagVideo.Hashtag', 'Country'),
            'limit' => APP_RECORDS_PER_PAGE,
            'offset' => $starting_point*APP_RECORDS_PER_PAGE,
            'order' => 'Video.id DESC'
        ));
    }

    public function getUserVideosCount($user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('count', array(
            'conditions' => array(
                'Video.user_id'=> $user_id,
                'Video.status'=> 2,
            ),
        ));
    }

    public function getFrequentlyUsedSounds()
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'conditions' => array(
                'Video.privacy_type'=> 'public',
                'Video.status' => 2
            ),
            'fields' => array( 'DISTINCT Video.sound_id','COUNT(*) count'),
            'group' => array('Video.sound_id'),
            'order' => 'COUNT(*) DESC',
        ));
    }

    public function getVideosAgainstSoundID($user_id,$device_id,$starting_id,$sound_id)
    {
        $this->Behaviors->attach('Containable');

        return $this->find('all', array(
            // 'fields' => array('id'),
            'conditions' => array(
                'Video.sound_id'=> $sound_id,
                'Video.privacy_type'=> 'public',
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification', 'User.Country', 'Sound', 'HashtagVideo.Hashtag', 'Country'),
            'limit' => APP_RECORDS_PER_PAGE,
            'offset' => $starting_id*APP_RECORDS_PER_PAGE,
            'order' => 'Video.view DESC'
        ));
    }

    public function getVideoReplies($user_id,$video_id,$starting_id)
    {
		$this->Behaviors->attach('Containable');

		return $this->find('all', array(
			'conditions' => array(
				'Video.main_video_id'=> $video_id,
				'Video.privacy_type'=> 'public',
				'Video.status'=> 2,
			),
			'contain' => array('Sound', 'User.PrivacySetting','User.PushNotification', 'HashtagVideo.Hashtag', 'Country'),
			'limit' => APP_RECORDS_PER_PAGE,
			'offset' => $starting_id*APP_RECORDS_PER_PAGE,
			'order' => 'Video.view DESC'
		));
    }

    public function getPromotedVideo()
    {
        $this->Behaviors->attach('Containable');
        return $this->find('first', array(
            // 'fields' => array('id'),
            'conditions' => array(
                'Video.promote'=> 1,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'HashtagVideo.Hashtag', 'Country'),
            'order' => 'rand()'
        ));
    }


    public function getRelatedVideosNotWatched($user_id,$device_id,$starting_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
           // 'fields' => array('id'),
            'conditions' => array('not exists '.
                '(SELECT id FROM video_watch as VideoWatch WHERE Video.id = VideoWatch.video_id AND VideoWatch.device_id ='.$device_id.')',
                //'Video.user_id !='=> $user_id,
                'Video.block'=> 0,
                'Video.privacy_type'=> 'public',
				'Video.main_video_id !=' => '0',
				'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting', 'User.PushNotification', 'Sound', 'HashtagVideo.Hashtag', 'Country'),
            'limit' => 10,
            'offset' => $starting_id*10,
            'order' => 'rand()'
            //'order' => 'Video.view DESC'
        ));
    }

    public function getRelatedVideosDemo($user_id,$device_id,$starting_id)
    {
        $this->Behaviors->attach('Containable');

        return $this->find('all', array(
            // 'fields' => array('id'),
            'conditions' => array(
                //'Video.user_id !='=> $user_id,
                'Video.block'=> 0,
                'Video.user_id'=> 1,
                'Video.privacy_type'=> 'public',
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'TopicVideo'),
            'limit' => 10,
            'offset' => $starting_id*10,
            'order' => 'rand()'
            //'order' => 'Video.view DESC'
        ));

    }

    public function getRelatedVideosNotWatchedDemo($user_id,$device_id,$starting_id)
    {
        $this->Behaviors->attach('Containable');

        return $this->find('all', array(
            // 'fields' => array('id'),
            'conditions' => array(
                'Video.user_id !='=> $user_id,
                'Video.block'=> 0,
                'Video.privacy_type'=> 'public',
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'HashtagVideo'),
            'limit' => 5,
            //'order' => 'Video.view DESC'
        ));
    }

    public function getRelatedVideosWatched($user_id,$device_id,$starting_id)
    {
        $this->Behaviors->attach('Containable');

        return $this->find('all', array(
            // 'fields' => array('id'),
            'conditions' => array(
               // 'Video.user_id !='=> $user_id,
                'Video.block'=> 0,
                'Video.privacy_type'=> 'public',
				'Video.main_video_id !=' => '0',
				'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'HashtagVideo.Hashtag', 'Country'),
            'limit' => 10,
            'offset' => $starting_id*10,
            'order' => 'rand()'
            //'order' => 'Video.view DESC'
        ));
    }

    public function getFollowingVideosNotWatched($user_id,$device_id,$starting_id,$ids)
    {
        $this->Behaviors->attach('Containable');

        return $this->find('all', array(
            // 'fields' => array('id'),
            'conditions' => array(
               // '(SELECT id FROM follower as Follower WHERE Video.user_id = Follower.receiver_id AND Follower.sender_id ='.$user_id.' LIMIT 1)',
                //'(SELECT id FROM video_watch as VideoWatch WHERE Video.id = VideoWatch.video_id AND VideoWatch.device_id ='.$device_id.' LIMIT 1)',
                'Video.user_id !='=> $user_id,
                'Video.privacy_type'=> 'public',
                'Video.block'=> 0,
                'Video.user_id IN'=> $ids,
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting','User.PushNotification','Sound', 'HashtagVideo.Hashtag', 'Country'),
            'limit' => 10,
            'offset' => $starting_id*10,

            'order' => 'rand()'
            //'order' => 'Video.view DESC'
        ));

    }


    public function getFollowingVideosWatched($user_id, $device_id, $starting_id)
    {
        $this->Behaviors->attach('Containable');

        return $this->find('all', array(
            // 'fields' => array('id'),
            'conditions' => array('exists '.
                '(SELECT id FROM follower as Follower WHERE Video.user_id = Follower.receiver_id AND Follower.sender_id ='.$user_id.')',
                'Video.user_id !='=> $user_id,
                'Video.block'=> 0,
                'Video.privacy_type'=> 'public',
                'Video.status'=> 2,
            ),
            'contain' => array('User.PrivacySetting', 'User.PushNotification', 'Sound', 'HashtagVideo.Hashtag', 'Country'),
            'limit' => 10,
            'offset' => $starting_id*10,

            'order' => 'rand()'
            //'order' => 'Video.view DESC'
        ));
    }

    public function getAllVideosAgainstSoundID($sound_id)
    {
        return $this->find('all', array(
            'conditions' => array(
                'Video.sound_id'=> $sound_id,
                'Video.status'=> 2
            ),
            'recursive' => -1,
        ));
    }

    public function updateSoundIDs($ids){
        $this->updateAll(
            array('sound_id' => 0),
            array('Video.sound_id IN' => $ids)
        );
    }

    public function getTopic($hashtags) {
				foreach ($hashtags as $hashtag) {
   	   		if ($hashtag['Hashtag']['featured'] == true)
							return $hashtag['Hashtag'];
				}
    }

    public function getUsersWhichHaveGreaterNoOfVideos($starting_point, $section, $country_id)
    {
        $this->Behaviors->attach('Containable');

				$conditions = [];

				if ($section == 1)
					 $conditions['User.role'] = 'publisher';
				else
					 $conditions['User.role'] = 'croaker';

				if ($country_id != 0)
	   				$conditions['Video.country_id'] = $country_id;

        return $this->find('all', array(
            'fields' => array( 'DISTINCT Video.user_id','User.*','sum(view) as total_views'),
            'conditions' => $conditions,
	    			'group' => array('Video.user_id'),
            'order' => 'total_views DESC',
            'limit'=>10,
            'offset' => $starting_point*10
        ));
    }

    public function getUserVideosLimit($user_id, $country_id = 0)
    {
    	$conditions = [];
        $conditions['Video.user_id'] = $user_id;
        $conditions['Video.privacy_type'] = "public";
        $conditions['Video.status'] = 2;
        if ($country_id != 0)
	   		$conditions['Video.country_id'] = $country_id;

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => array('User', 'User.PrivacySetting', 'User.PushNotification', 'Sound', 'Country', 'HashtagVideo.Hashtag'),
            'conditions' => $conditions,
            'limit'=>5,
            'order' => 'Video.view DESC',
        ));
    }

    public function countUserVideos($user_id, $country_id = 0)
    {
    	$conditions = [];
        $conditions['Video.user_id'] = $user_id;
        $conditions['Video.privacy_type'] = "public";
        $conditions['Video.status'] = 2;
        if ($country_id != 0)
	   		$conditions['Video.country_id'] = $country_id;

        return $this->find('count', array(
            //'contain' => array('Video'),
            'conditions' => $conditions
        ));
    }
}

?>
