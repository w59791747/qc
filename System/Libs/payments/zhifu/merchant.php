<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号1118004517的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAMSxPK9XHymx14oZ
b97s+dIaJNQNOgTj0L6GqzD33aH1ygOKQ6pV2/rjx0RvZRusvZmBQnUOo4ywGSkM
trmtaUl5L+5FUdZvuliQrboO5T5lHDvZciKedXNATcedodbYU1U4l6+cl02arF3U
sxmbjMRkdKxHdHVL/2v8gl8b5o4nAgMBAAECgYEApFR3oP6m2IzuyUBLSPKwHase
DdQnHyK+qg+VQ0oy7zeM2bxQoCUvrsNTHof670448eEehvH+DnKp8rPeje6lwPYR
PRA1QarxctaPuiGaInfKpATwa13BDMpL+Od4/Cu28XTFHlxgVqyxkbUjLBBw/ehn
+fWQNjl28TY5cgHcuaECQQD4RVB1oyaImo8mUKlQbrfTiaq17wtEL6cSXT1aw2o9
om0pXVFlIA9yCC1/EeDZ7v0q9NN/xxClwdjSs2/nHZ4/AkEAytDZcdNj2dTP6hD7
rfUXCAGLY86SBGHukz5tdoYY33Y5WSwfDbhUYYzFsX+XFxQov4Rq7XasL3WyJZQi
f8tmGQJBAI90HaWbe+x6wjQ1b1/WWcmVRlqRoUTo8/Scg5MqTv0GGWVJUnrIJ6SQ
LRm7Tt0eRpLHnF28vFXKyGoW3JYCeykCQEMPx7aRdUTDz3PP5chhcBofmJ2J4lI8
p4xcagl00aiiCNhpdGMu/ge7amsEi0sASXD6MfkO8mRHAH7YeofTE5ECQQDrqQU0
bHZSIrbVv9BXaoXzLtV926N5CnPCojo0jZ8AiA2FCgqg8grOGxCpnYzmOhCN4Z1y
nNcI12quVJzHbnQA
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到智付商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDEsTyvVx8psdeKGW/e7PnSGiTU
DToE49C+hqsw992h9coDikOqVdv648dEb2UbrL2ZgUJ1DqOMsBkpDLa5rWlJeS/u
RVHWb7pYkK26DuU+ZRw72XIinnVzQE3HnaHW2FNVOJevnJdNmqxd1LMZm4zEZHSs
R3R1S/9r/IJfG+aOJwIDAQAB
-----END PUBLIC KEY-----';
	
/**
1)dinpay_public_key，智付公钥，每个商家对应一个固定的智付公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为智付商家后台"公钥管理"->"智付公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号1118004517的智付公钥，请自行复制对应商户号的智付公钥进行调整和替换。
3）使用智付公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
		$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCXpcKThSTxHWLDkEpM
70qYMMocUqa900HNGymiF6wS2y/BsB6pyv9WCg8k9Wwld8XixIKOjJkN
gEqIyvnE5e2moDw3qER+gq5sk2CJ2irQc2OYs4DMc8GfU3jjLm07V15j
ZDRRANRolzwLQAt1mznL535vn4Tp8ZehUh+3UR/jzwIDAQAB 
-----END PUBLIC KEY-----'; 	
	



?>