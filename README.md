# circuit-breaker

[![CircleCI](https://circleci.com/gh/aguimaraes/circuit-breaker/tree/master.svg?style=svg)](https://circleci.com/gh/aguimaraes/circuit-breaker/tree/master)

### Usage example

```php
$cb = new Aguimaraes\CircuitBreaker(
    new Aguimaraes\Adapter\ACPu()
);

// number of errors necessary to open the circuit
$cb->setThreshold(10, 'my-service'); 

// wait x seconds to check if service is back
$cb->setTimeout(60, 'my-service');

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
