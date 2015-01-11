# Roomba Simulator

「ルンバはどんなアルゴリズムで動かせば部屋を効率よく綺麗にできるのか？」を実験したい。
そんなときに使う簡易シミュレーターです。

## インストール

インストールにはcomposerを使ってください。
packagistにあげていないのでrepositoriesも指定が必要です。

```
{
    "require" : {
        "sat8bit/roombasim" : "dev-master",
        "symfony/console" : "3.0.*@dev"
    },
    "repositories": [
        {
            "type":"vcs",
            "url": "git://github.com/sat8bit/roombasim.git" 
        }
        
    ]
}
```

## Usage

コマンドのUsageは次の通りです。

```
$ vendor/bin/roombasim roombasim -help
Usage:
 roombasim [--step="..."] [--disp] [--room="..."] ai

Arguments:
 ai                    Enter the name of RoombaAI that implements the sat8bit\Roomba\RoombaAIInterface the full path.

Options:
 --step                step number. (default: 10000)
 --disp                display by step.
 --room                Enter the name of Room that extends the sat8bit\RoombaSim\Room\AbstractRoom the full path. (default: "RectangleRoom15x15")
```

## つかいかた

とりあえず動かしてみましょう。壁に当たるとランダムで回転するRoombaがデフォルトで用意してあります。

```
$ vendor/bin/roombasim roombasim 'sat8bit\RoombaSim\Roomba\RoombaAISample' --step 100000
Result : Cleaned(2935)
```

下記は最大の移動回数を示します。この回数を超えて部屋が綺麗にならなかった場合、失敗とみなします。

```
--step 100000
```

出力結果の2935は、実際にかかったstep数です。

```
Result : Cleaned(2935)
```

動きを確認したい場合は下記のオプションを指定します。

```
--disp
```

掃除するRoomを変更する場合は下記のオプションで指定します。

```
--room 'RectangleRoom15x15'
```

## Roombaの作り方

Roombaを作成するために必要なのは１つのクラスと１つのインターフェースを理解することです。

### Motion

MotionはRoombaの行動を示すクラスです。

```
<?php

use sat8bit\RoombaSim\Roomba\Motion;

new Motion(60, 100);
```

上記が表す動きは、「時計回りに60度回転し、100進む」です。

### RoombaAIInterface

RoombaAIInterfaceは、Roombaに搭載するAIのインターフェースです。

```
<?php

namespace sat8bit\RoombaSim\Roomba;

interface RoombaAIInterface
{
    /**
     * when hit.
     *
     * @param int $distance
     * @return Motion
     */
    public function hit($distance);

    /**
     * when ran.
     *
     * @param int $distance
     * @return Motion
     */
    public function ran($distance);
}
```

hitメソッドは、壁にあたったときの動作を返します。

```
    /**
     * when hit.
     *
     * @param int $distance
     * @return Motion
     */
    public function hit($distance)
    {
        // 壁にあたったら60度回転し2進む
        return new Motion(60, 2);
    }
```

ranメソッドは、壁にあたる前に指定した距離を移動した際の動作を返します。

```
    /**
     * when ran.
     *
     * @param int $distance
     * @return Motion
     */
    public function ran($distance)
    {
        // 指定した距離を移動したら120度回転し10進む
        return new Motion(120, 10);
    }
```

だいたいこんな感じになります。

```
<?php

namespace sat8bit\RoombaSim\Roomba;

use sat8bit\RoombaSim\Roomba\RoombaAIInterface;
use sat8bit\RoombaSim\Roomba\Motion;

class MyRoombaAI implements RoombaAIInterface
{
    /**
     * when hit.
     *
     * @param int $distance
     * @return Motion
     */
    public function hit($distance)
    {
        // 壁にあたったら60度回転し2進む
        return new Motion(60, 2);
    }

    /**
     * when ran.
     *
     * @param int $distance
     * @return Motion
     */
    public function ran($distance)
    {
        // 指定した距離を移動したら120度回転し10進む
        return new Motion(120, 10);
    }
}
```

作成したクラスをautoloadできるような場所に配置し、実行する際に名前空間を含んだクラス名を渡します。

```
vagrant$ bin/roombasim roombasim 'sat8bit\RoombaSim\Roomba\MyRoombaAI' --step 100000
Result : Not cleaned.
```

綺麗にできませんでしたね。

# TODO

- 部屋を拡張して障害物とか利用できるようにする
- 現在は移動回数で評価するが、回転時間も考慮するような評価方法に変更する
