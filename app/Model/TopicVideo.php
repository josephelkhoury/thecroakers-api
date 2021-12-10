<?php


class TopicVideo extends AppModel
{

    public $useTable = 'topic_video';

    public $belongsTo = array(
        'Topic' => array(
            'className' => 'Topic',
            'foreignKey' => 'topic_id',



        ),'Video' => array(
            'className' => 'Video',
            'foreignKey' => 'video_id',



        ),

    );



    public function getDetails($id)
    {
        return $this->find('first', array(
            'conditions' => array(


                'TopicVideo.id'=> $id,




            )
        ));
    }

    public function getTopicVideosWithLimit($topic_id,$starting_point)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => array('Video.Sound','Video.User.PrivacySetting','Video.User.PushNotification','Topic'),
            'conditions' => array(



                'TopicVideo.topic_id'=> $topic_id,
                'Video.privacy_type'=> "public",




            ),
            'limit' => APP_RECORDS_PER_PAGE,
            'offset' => $starting_point * APP_RECORDS_PER_PAGE,

            'order' => 'Video.view DESC',
        ));
    }

    public function getTopicVideos($topic_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => array('Video.Sound','Video.User','Topic'),
            'conditions' => array(



                'TopicVideo.topic_id'=> $topic_id,
                'Video.privacy_type'=> "public",




            ),
            'order' => 'Video.view DESC',
        ));
    }

    public function getTopicVideosLimit($topic_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => array('Video.Sound','Video.User','Topic'),
            'conditions' => array(



                'TopicVideo.topic_id'=> $topic_id,
                'Video.privacy_type'=> "public",




            ),
            'limit'=>5,
            'order' => 'Video.view DESC',
        ));
    }

    public function countTopicViews($topic_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('first', array(
            'contain' => array('Video.Sound','Video.User'),
            'conditions' => array(



                'TopicVideo.topic_id'=> $topic_id,
                'Video.privacy_type'=> "public",




            ),
            'fields' => array( 'sum(Video.view) as total_sum'),
            'order' => 'Video.view DESC',
        ));
    }
    public function countTopicVideos($topic_id)
    {

        return $this->find('count', array(
            //'contain' => array('Video'),
            'conditions' => array(



                'TopicVideo.topic_id'=> $topic_id,
                'Video.privacy_type'=> "public",





            ),



        ));
    }
    public function getTopicsWhichHasGreaterNoOfVideos($starting_point)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'fields' => array( 'DISTINCT TopicVideo.topic_id','Topic.*','sum(Video.view) as total_views'),

            'group' => array('TopicVideo.topic_id'),
            'order' => 'total_views DESC',
            'limit'=>10,
            'offset' => $starting_point*10


        ));
    }

    public function ifExist($data)
    {
        return $this->find('first', array(
            'conditions' => array(



                'TopicVideo.topic_id'=> $data['topic_id'],
                'TopicVideo.video_id'=> $data['video_id'],




            )
        ));
    }







}
?>
