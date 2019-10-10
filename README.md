# circuit-breaker

[![CircleCI](https://circleci.com/gh/aguimaraes/circuit-breaker.svg?style=svg)](https://circleci.com/gh/aguimaraes/circuit-breaker)
[![codecov](https://codecov.io/gh/aguimaraes/circuit-breaker/branch/master/graph/badge.svg)](https://codecov.io/gh/aguimaraes/circuit-breaker)

### Usage example

```php
$cb = new Aguimaraes\CircuitBreaker(
    new Aguimaraes\Adapter\ACPu()
);

// number of errors necessary to open the circuit
$cb->setThreshold('my-service', 10); 

// wait x seconds to check if service is back
$cb->setTimeout('my-service', 60);

$response = null;

if ($cb->isAvailable('my-service')) {
    try {
        
        $response = $service->makeCall();
        $cb->reportSuccess('my-service');
        
    } catch (ServiceException $e) {
        
        $cb->reportFailure('my-service');
        
    } catch (NonServiceRelatedException $e) {
        
        // something went wrong and it was not the service fault
        
    }
}
```
