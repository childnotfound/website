<?php
class MissingChildren extends Model {
  public function to_json()
  {
    $json = array('name' => $this->name,
                  'sex'  => $this->gender,
                  'avatar' => $this->photo_url,
                  'currentAge' => $this->current_age,
                  'missingAge' => $this->lost_age,
                  'missingDate' => $this->lost_date,
                  'missingRegion' => $this->area,
                  'missingCause' => $this->reason,
                  'missingLocation' => $this->location,
                  'character' => $this->description);
    return json_encode($json);
  }
}
