<?php

class Google_Service_LumSites extends Google_Service
{  
  /**
   * Constructs the internal representation of the LumSites service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client)
  {
    parent::__construct($client);
    $this->rootUrl = 'https://lumsites.appspot.com/';
    $this->servicePath = '_ah/api/lumsites/v1/';
    $this->version = 'v1';
    $this->serviceName = 'lumsites';

  }
}



