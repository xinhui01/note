## laravel 中间件实现
- 主要代码
```php
    protected function sendRequestThroughRouter($request)
    {
        $this->app->instance('request', $request);

        Facade::clearResolvedInstance('request');

        $this->bootstrap();

        return (new Pipeline($this->app))
                    ->send($request)
                    ->through($this->app->shouldSkipMiddleware() ? [] : $this->middleware)
                    ->then($this->dispatchToRouter());
    }
```
```php
    public function then(Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes), $this->carry(), $this->prepareDestination($destination)
        );

        return $pipeline($this->passable);
    }
    protected function carry() {
        //array_reduce使用的闭包
        //$stack 就是中间件
        return function($stack, $pip) {
            //array_reduce压缩起来的闭包函数栈是这一层
            return function($passable) use($stack, $pip) {
                return $pip->handle($passable, $stack);
            };
        };
    }
```
- array_reverse 反转数组,倒叙
- array_reduce 实现类似
```php
function array_reduce($arr, $callback, $init = null)
{
    $ret = $init;
    foreach($arr as $item){
        $ret = $callback($ret,$item);
    }
    return $ret;
}
```