<?php


class HashtagVideo extends AppModel
{

    public $useTable = 'hashtag_video';

    public $belongsTo = array(
        'Hashtag' => array(
            'className' => 'Hashtag',
            'foreignKey' => 'hashtag_id',

        ),'Video' => array(
            'className' => 'Video',
            'foreignKey' => 'video_id',
        ),
    );

    public function getDetails($id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'HashtagVideo.id'=> $id,
            )
        ));
    }

    public function getHashtagVideosWithLimit($hashtag_id,$starting_point)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => array('Video.Sound','Video.User.PrivacySetting','Video.User.PushNotification', 'Video.Country', 'Hashtag'),
	    'joins' => array(
		array(
		    'table' => 'user',
		    'alias' => 'User',
		    'type' => 'LEFT',
		    'conditions' => array(
			'User.id = Video.user_id'
		    ),
		),
	    ),
            'conditions' => array(
                'HashtagVideo.hashtag_id' => $hashtag_id,
                'Video.privacy_type' => "public",
		'User.role' => 'publisher',
		'Video.main_video_id' => null
            ),
            'limit' => APP_RECORDS_PER_PAGE,
            'offset' => $starting_point * APP_RECORDS_PER_PAGE,

            'order' => 'Video.view DESC',
        ));
    }

    public function getHashtagVideos($hashtag_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => array('Video.Sound','Video.User', 'Video.Country', 'Hashtag'),
            'conditions' => array(
                'HashtagVideo.hashtag_id'=> $hashtag_id,
                'Video.privacy_type'=> "public",
		'Video.User.role' => 'publisher',
		'Video.main_video_id' => null
            ),
            'order' => 'Video.view DESC',
        ));
    }

    public function getHashtagVideosLimit($hashtag_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => array('Video.Sound','Video.User', 'Video.Country', 'Hashtag'),
            'joins' => array(
		array(
		    'table' => 'user',
		    'alias' => 'User',
    		    'type' => 'LEFT',
		    'conditions' => array(
		    	'User.id = Video.user_id'
		    ),
		),
	    ),
	    'conditions' => array(
                'HashtagVideo.hashtag_id'=> $hashtag_id,
                'Video.privacy_type'=> "public",
            	'User.role' => "publisher"
	    ),
            'limit'=>5,
            'order' => 'Video.view DESC',
        ));
    }

    public function countHashtagViews($hashtag_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('first', array(
            'contain' => array('Video.Sound','Video.User'),
	    'joins' => array(
		array(
		    'table' => 'user',
		    'alias' => 'User',
		    'type' => 'LEFT',
		    'conditions' => array(
			'User.id = Video.user_id'
		    ),
		),
	    ),
            'conditions' => array(
                'HashtagVideo.hashtag_id'=> $hashtag_id,
                'Video.privacy_type'=> "public",
		'User.role' => "publisher"
            ),
            'fields' => array( 'sum(Video.view) as total_sum'),
            'order' => 'Video.view DESC',
        ));
    }

    public function countHashtagVideos($hashtag_id)
    {
        return $this->find('count', array(
            'joins' => array(
		array(
		    'table' => 'user',
		    'alias' => 'User',
		    'type' => 'LEFT',
		    'conditions' => array(
		        'User.id = Video.user_id'
		    ),
		),
	     ),
            'conditions' => array(
                'HashtagVideo.hashtag_id'=> $hashtag_id,
                'Video.privacy_type'=> "public",
		'User.role' => 'publisher'
            ),
        ));
    }

    public function getHashtagsWhichHasGreaterNoOfVideos($starting_point, $country_id)
    {
        $this->Behaviors->attach('Containable');

        $conditions = [];
        $conditions['Hashtag.featured'] = true;
        if ($country_id != 0)
	   				$conditions['Video.country_id'] = $country_id;

        return $this->find('all', array(
	    			'conditions' => $conditions,
            'fields' => array( 'DISTINCT HashtagVideo.hashtag_id','Hashtag.*','sum(Video.view) as total_views'),
            'group' => array('HashtagVideo.hashtag_id'),
            'order' => 'total_views DESC',
            'limit'=>10,
            'offset' => $starting_point*10
        ));
    }

    public function ifExist($data)
    {
        return $this->find('first', array(
            'conditions' => array(
                'HashtagVideo.hashtag_id'=> $data['hashtag_id'],
                'HashtagVideo.video_id'=> $data['video_id'],
            )
        ));
    }
}
?>
