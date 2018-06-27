<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;

class ChartController extends Controller
{
    use Helpers;

    public function ambient(Request $request) {
      $site_ids = $request->input('sites');
      $type = $request->input('type');
      $from = $request->input('from');
      $to = $request->input('to');
      $componentIDs = $request->input('components');

      // set from and to as carbon dates
      $from = \Carbon\Carbon::createFromFormat('Y-m-d', $from);
  		$to = \Carbon\Carbon::createFromFormat('Y-m-d', $to);

      // fetch sites based on the provided site ID's
      $sites = \App\Models\Site::whereIn('id', $site_ids)->get();

      $site_names = [];
      foreach($sites as $site) {
        $site_names[] = $site->instrument_name;
      }

      // fetch component based on the provided component ID's
      $components = \App\Models\Air::whereIn('id', $componentIDs)->get();

      $component_names = [];
      foreach($components as $component) {
        $component_names[] = !empty(trim($component->alias)) ?
          $component->alias :
          $component->component_name;
      }

      $data = [
  			'sites' => implode(', ', $site_names),
  			'component_names' => implode('/', $component_names),
  			'dateRange' => ($from == $to) ? $from : $from . ' to ' . $to,
  			'xData' => [],
  			'datasets' => []
  		];


      foreach ($site_names as $site_name) {
  			foreach($component_names as $component_name) {
  				$data['datasets'][] = [
  					'name' => $component_name . ' (' . $site_name . ')',
  					'data' => [],
  					 ($type=='amount') ? 'time' : 'amount' => [],
  					'txo_ids' => [],
  					'filenames' => []
      		];
      	}
  		}

      while($from <= $to) {
  			for($i=0; $i<=23; $i++) {
  				$data['xData'][] = $from->format('m/d/Y') . ' ' . ($i<10 ? '0' : '') . $i . ':00';
  			}

  			foreach($site_ids as $key => $site_id) {
  				
          $txoDumps = $this->api->with(
              [
                'standard' => 'S',
                'sample_date' => $from->format('Y-m-d'),
                'component_names' => $component_names
              ]
            )
            ->post('sites/'.$site_id.'/dumps?token='.$request->input('token'));

  				foreach($component_names as $component_name) {
  						if(
  							strtoupper(trim($component_name))=='TNMHC' ||
  							strtoupper(trim($component_name))=='TNMTC'
  						) {
  							continue;
  						}

  						$_data['datasets'] = [];
  						$_data[($type=='amount') ? 'time' : 'amount'] = [];
  						$_data['txo_ids']	= [];
  						$_data['filenames']	= [];

  						for($i=0; $i<=23; $i++) {
  							$_data['datasets'][] = null;
  							$_data[($type=='amount') ? 'time' : 'amount'][] = null;
  					  	$_data['txo_ids'][] = null;
  					  	$_data['filenames'][] = null;
              }

              foreach($txoDumps as $txoDump) {
                  $componentValues = $txoDump->componentValues->filter(function($componentValue) use ($component_name) {
              			return strtoupper(trim($componentValue->air->component_name)) == strtoupper(trim($component_name)) ||
                      strtoupper(trim($componentValue->air->alias)) == strtoupper(trim($component_name));
                  });

  								if(count($componentValues)==0) {
  									continue;
  								}

  								foreach ($componentValues as $componentValue) {
  									$hour = $componentValue->txo->sample_hour;

                    // bypass ambient RT for peaks with amount lesser than 0.4
                    if($type=='amount' && $componentValue->amount==null) {
  										continue;
  									}

                    $_data['datasets'][$hour] = ($componentValue->{$type}==-999.999) ? 0 : $componentValue->{$type};
  									$_data[($type=='amount') ? 'time' : 'amount'][$hour] = ($componentValue->{($type=='amount') ? 'time' : 'amount'}==-999.999) ? 0 : $componentValue->{($type=='amount') ? 'time' : 'amount'};
                    $_data['txo_ids'][$hour] = $componentValue->txo->id;
                    $_data['filenames'][$hour] = $componentValue->txo->filename;
                  }

                  usleep(10);
              }

              $index = 0;
              foreach($data['datasets'] as $dataset) {
  							if( strcasecmp($dataset['name'], $component_name . ' (' . $site_names[$key] . ')')  == 0 ) {
  								break;
  							}
                $index++;
              }

              foreach ($_data['datasets'] as $amount) {
                array_push($data['datasets'][$index]['data'], $amount);
              }

  						foreach ($_data[($type=='amount') ? 'time' : 'amount'] as $temp) {
                array_push($data['datasets'][$index][($type=='amount') ? 'time' : 'amount'], $temp);
              }

              foreach ($_data['txo_ids'] as $txo_id) {
                array_push($data['datasets'][$index]['txo_ids'], $txo_id);
              }

              foreach ($_data['filenames'] as $filename) {
                array_push($data['datasets'][$index]['filenames'], $filename);
              }
          }

          unset($txoDumps);

  				if(in_array('TNMHC', $component_names)) {

            $txoDumpsTNMHC = $this->api->with(
                [
                  'standard' => 'S',
                  'sample_date' => $from->format('Y-m-d'),
                  'component_names' => ['METHANE']
                ]
              )
              ->post('sites/'.$site_id.'/dumps?token='.$request->input('token'));

  					$_data['datasets'] = [];
  					$_data[($type=='amount') ? 'time' : 'amount'] = [];
  					$_data['txo_ids'] = [];
  					$_data['filenames'] = [];

  					for($i=0; $i<=23; $i++) {
  						$_data['datasets'][] = 0;
  						$_data[($type=='amount') ? 'time' : 'amount'][] = 0;
  						$_data['txo_ids'][] = [];
  						$_data['filenames'][] = [];
  					}

						foreach($txoDumpsTNMHC as $txoDump) {

              $componentValues = $txoDump->componentValues;
							$hour = $txoDump->sample_hour;

							if($type=='amount') {
								$_data['datasets'][$hour] += $txoDump->componentsTotal->pp_carbon;
								$_data[($type=='amount') ? 'time' : 'amount'][$hour] += $txoDump->componentsTotal->method_rt;
							} else {
								$_data['datasets'][$hour] += $txoDump->componentsTotal->method_rt;
								$_data[($type=='amount') ? 'time' : 'amount'][$hour] += $txoDump->componentsTotal->pp_carbon;
							}

							// less methane amount
							if(count($componentValues)>0) {
								foreach ($componentValues as $componentValue) {
									if($type=='amount' && $componentValue->amount==null) {
										continue;
									}
									$_data['datasets'][$hour] -= ($componentValue->{$type}==-999.999) ? 0 : $componentValue->{$type};
									$_data[($type=='amount') ? 'time' : 'amount'][$hour] -= ($componentValue->{($type=='amount') ? 'time' : 'amount'}==-999.999) ? 0 : $componentValue->{($type=='amount') ? 'time' : 'amount'};
                  usleep(10);
								}
								$_data['datasets'][$hour] = $_data['datasets'][$hour] < 0 ? 0 : $_data['datasets'][$hour];
								$_data[($type=='amount') ? 'time' : 'amount'][$hour] = $_data[($type=='amount') ? 'time' : 'amount'][$hour] < 0 ? 0 : $_data[($type=='amount') ? 'time' : 'amount'][$hour];
							}

							if(!in_array($txoDump->id, $_data['txo_ids'][$hour])) {
								$_data['txo_ids'][$hour][] = $txoDump->id;
							}

							if(!in_array($txoDump->filename, $_data['filenames'][$hour])) {
								$_data['filenames'][$hour][] = $txoDump->filename;
							}

              usleep(10);
            }

						$index = 0;
						$found = false;
						foreach($data['datasets'] as $dataset) {
							if($dataset['name'] == 'TNMHC (' . $site_names[$key] . ')') {
								$found = true;
								break;
							}
							$index++;
						}

						if($found) {
							foreach ($_data['datasets'] as $amount) {
								array_push($data['datasets'][$index]['data'], $amount);
							}

							foreach ($_data[($type=='amount') ? 'time' : 'amount'] as $temp) {
								array_push($data['datasets'][$index][($type=='amount') ? 'time' : 'amount'], $temp);
							}

							foreach ($_data['txo_ids'] as $txo_id) {
								array_push($data['datasets'][$index]['txo_ids'], implode(',', $txo_id));
							}

							foreach ($_data['filenames'] as $filename) {
								array_push($data['datasets'][$index]['filenames'], implode(',', $filename));
							}
						}

            unset($txoDumpsTNMHC);
  				}

  				if(in_array('TNMTC', $component_names)) {

            $txoDumpsTNMTC = $this->api->with(
                [
                  'standard' => 'S',
                  'sample_date' => $from->format('Y-m-d')
                ]
              )
              ->post('sites/'.$site_id.'/dumps?token='.$request->input('token'));

  					$_data['datasets'] = [];
  					$_data[($type=='amount') ? 'time' : 'amount'] = [];
  					$_data['txo_ids'] = [];
  					$_data['filenames'] = [];

  					for($i=0; $i<=23; $i++) {
  						$_data['datasets'][] = 0;
  						$_data[($type=='amount') ? 'time' : 'amount'][] = 0;
  						$_data['txo_ids'][] = [];
  						$_data['filenames'][] = [];
  					}

  					foreach($txoDumpsTNMTC as $txoDump) {
  						$componentValues = $txoDump->componentValues;
  						foreach($componentValues as $componentValue) {
  							$hour = $componentValue->txo->sample_hour;

  							if($type=='amount' && $componentValue->amount==null) {
  								continue;
  							}

  							// Exclude METHANE
  							if(
  								strtoupper($componentValue->component_name)=='METHANE' ||
  								empty(trim($componentValue->component_name))
  							) {
  								continue;
  							}

  							$_data['datasets'][$hour] += ($componentValue->{$type}==-999.999) ? 0 : $componentValue->{$type};
  							$_data[($type=='amount') ? 'time' : 'amount'][$hour] += ($componentValue->{($type=='amount') ? 'time' : 'amount'}==-999.999) ? 0 : $componentValue->{($type=='amount') ? 'time' : 'amount'};

  							if(!in_array($componentValue->txo->id, $_data['txo_ids'][$hour])) {
  								$_data['txo_ids'][$hour][] = $componentValue->txo->id;
  							}

  							if(!in_array($componentValue->txo->filename, $_data['filenames'][$hour])) {
  								$_data['filenames'][$hour][] = $componentValue->txo->filename;
  							}
                usleep(10);
  						}
              usleep(10);
  					}

  					$index = 0;
  					$found = false;
  					foreach($data['datasets'] as $dataset) {
  						if($dataset['name'] == 'TNMTC (' . $site_names[$key] . ')') {
  							$found = true;
  							break;
  						}
  						$index++;
  					}

  					if($found) {
  						foreach ($_data['datasets'] as $amount) {
  							array_push($data['datasets'][$index]['data'], $amount);
  						}

  						foreach ($_data[($type=='amount') ? 'time' : 'amount'] as $temp) {
  							array_push($data['datasets'][$index][($type=='amount') ? 'time' : 'amount'], $temp);
  						}

  						foreach ($_data['txo_ids'] as $txo_id) {
  							array_push($data['datasets'][$index]['txo_ids'], implode(',', $txo_id));
  						}

  						foreach ($_data['filenames'] as $filename) {
  							array_push($data['datasets'][$index]['filenames'], implode(',', $filename));
  						}
  					}

            unset($txoDumpsTNMTC);
          }

          usleep(10);
        }

  			$from->addDay();
        usleep(10);
  		}

      return $data;
    }
}
