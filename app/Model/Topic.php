<?php


class Topic extends AppModel
{

    public $useTable = 'topic';

    public function getDetails($id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'Topic.id'=> $id,
            )
        ));
    }

    public function getTopics()
    {
	$this->Behaviors->attach('Containable');

	return $this->find('all', array(
	// 'fields' => array('id'),
	'order' => 'Topic.id ASC',
	'recursive'=>-1
	));
    }

    public function ifExist($name)
    {
        return $this->find('first', array(
            'conditions' => array(



                'Topic.name'=> $name,




            )
        ));
    }

    public function getSearchResults($keyword,$starting_point){



        return $this->find('all', array(

            'conditions' => array(




                'Topic.name Like' => "$keyword%"

            ),

            'limit' => 10,
            'offset' => $starting_point*10,






            'recursive' => 0


        ));

    }







}
?>
