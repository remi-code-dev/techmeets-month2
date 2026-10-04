<?php

return [

    /*
    |--------------------------------------------------------------------------
    | バリデーションメッセージ
    |--------------------------------------------------------------------------
    |
    | ここにないルールは、フォールバック言語（英語）のメッセージが使われる。
    |
    */

    'accepted' => ':attributeを承認してください。',
    'active_url' => ':attributeは有効なURLではありません。',
    'after' => ':attributeには:dateより後の日時を指定してください。',
    'after_or_equal' => ':attributeには:date以降の日時を指定してください。',
    'alpha' => ':attributeは英字のみで入力してください。',
    'alpha_dash' => ':attributeは英数字・ハイフン・アンダースコアのみで入力してください。',
    'alpha_num' => ':attributeは英数字のみで入力してください。',
    'array' => ':attributeは配列で指定してください。',
    'before' => ':attributeには:dateより前の日時を指定してください。',
    'before_or_equal' => ':attributeには:date以前の日時を指定してください。',
    'between' => [
        'array' => ':attributeは:min〜:max個で指定してください。',
        'file' => ':attributeは:min〜:maxKBのファイルを指定してください。',
        'numeric' => ':attributeは:min〜:maxの間で指定してください。',
        'string' => ':attributeは:min〜:max文字で入力してください。',
    ],
    'boolean' => ':attributeにはtrueかfalseを指定してください。',
    'confirmed' => ':attributeが確認用の値と一致しません。',
    'current_password' => 'パスワードが正しくありません。',
    'date' => ':attributeは正しい日付ではありません。',
    'date_format' => ':attributeは:formatの形式で入力してください。',
    'different' => ':attributeと:otherには異なる値を指定してください。',
    'digits' => ':attributeは:digits桁で入力してください。',
    'digits_between' => ':attributeは:min〜:max桁で入力してください。',
    'email' => ':attributeには正しい形式のメールアドレスを入力してください。',
    'exists' => '選択された:attributeは正しくありません。',
    'file' => ':attributeにはファイルを指定してください。',
    'filled' => ':attributeを入力してください。',
    'image' => ':attributeには画像ファイルを指定してください。',
    'in' => '選択された:attributeは正しくありません。',
    'integer' => ':attributeは整数で入力してください。',
    'lowercase' => ':attributeは小文字で入力してください。',
    'max' => [
        'array' => ':attributeは:max個以下で指定してください。',
        'file' => ':attributeは:maxKB以下のファイルを指定してください。',
        'numeric' => ':attributeは:max以下で指定してください。',
        'string' => ':attributeは:max文字以内で入力してください。',
    ],
    'mimes' => ':attributeには:valuesタイプのファイルを指定してください。',
    'min' => [
        'array' => ':attributeは:min個以上で指定してください。',
        'file' => ':attributeは:minKB以上のファイルを指定してください。',
        'numeric' => ':attributeは:min以上で指定してください。',
        'string' => ':attributeは:min文字以上で入力してください。',
    ],
    'not_in' => '選択された:attributeは正しくありません。',
    'numeric' => ':attributeは数値で入力してください。',
    'password' => [
        'letters' => ':attributeには英字を1文字以上含めてください。',
        'mixed' => ':attributeには大文字と小文字をそれぞれ1文字以上含めてください。',
        'numbers' => ':attributeには数字を1文字以上含めてください。',
        'symbols' => ':attributeには記号を1文字以上含めてください。',
        'uncompromised' => 'この:attributeは過去に漏えいしたことがあります。別の:attributeを指定してください。',
    ],
    'regex' => ':attributeの形式が正しくありません。',
    'required' => ':attributeは必須です。',
    'required_if' => ':otherが:valueの場合、:attributeは必須です。',
    'required_with' => ':valuesを指定する場合、:attributeは必須です。',
    'same' => ':attributeと:otherが一致しません。',
    'size' => [
        'array' => ':attributeは:size個で指定してください。',
        'file' => ':attributeは:sizeKBのファイルを指定してください。',
        'numeric' => ':attributeは:sizeを指定してください。',
        'string' => ':attributeは:size文字で入力してください。',
    ],
    'string' => ':attributeは文字列で入力してください。',
    'timezone' => ':attributeには正しいタイムゾーンを指定してください。',
    'unique' => 'その:attributeは既に使われています。',
    'url' => ':attributeには正しい形式のURLを入力してください。',

    /*
    |--------------------------------------------------------------------------
    | 項目名
    |--------------------------------------------------------------------------
    |
    | FormRequest の attributes() で個別に指定していない項目の日本語名。
    |
    */

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'current_password' => '現在のパスワード',
        'body' => '投稿内容',
    ],

];
