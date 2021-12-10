<?php



class AppSlider extends AppModel
{

    public $useTable = 'app_slider';

    /*public function afterFind($results, $primary = false) {
      foreach ($results as $key => $val) {
	if (isset($val['AppSlider']['image'])) {
	  $results[$key]['AppSlider']['image'] = BASE_URL.$results[$key]['AppSlider']['image'];
	}
      }
      return $results;
    }*/

    public function getImages()
    {
        return $this->find('all');


    }


    public function getDetails($id)
    {
        return $this->find('first', array(


            // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),

            'conditions' => array(



                'AppSlider.id' => $id


            ),
        ));


    }

    public function getAll()
    {
        return $this->find('all');


    }

    public function getAppSlidersCount()
    {
        return $this->find('count');
    }

    public function deleteAppSlider($id)
    {
        return $this->deleteAll(
            [
                'AppSlider.id' => $id

            ],
            false # <- single delete statement please
        );
    }
}

?>
