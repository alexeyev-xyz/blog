---
layout: post
title:"Навигационные панели в разных окружениях"
date: 2018-01-31 00:00:00 +0300
tags: [Imported]
---
# Навигационные панели в разных окружениях

Идея не новая, однако, возможно, поможет вам сохранить вам кучу нервов, когда вы решите протестировать новую фичу и случайно не сделаете это на production-базе.

Суть в том, чтобы менять цвет навигационной панели на сайте в разных окружениях (dev, stage, prod). Для этого будем автоматически менять ее цвет из md5-хеша адреса сайта.

<figure id="b968" class="graf graf--figure graf-after--p">

<div class="aspectRatioPlaceholder is-locked"></div>

<div class="aspectRatioPlaceholder is-locked">[![prod](https://vlaim.s3.amazonaws.com/uploads/2018/01/prod.png)](https://vlaim.s3.amazonaws.com/uploads/2018/01/prod.png) Черная панель — production [![dev](https://vlaim.s3.amazonaws.com/uploads/2018/01/dev.png)](https://vlaim.s3.amazonaws.com/uploads/2018/01/dev.png) Так выглядит панель в dev-окружении  [![stage](https://vlaim.s3.amazonaws.com/uploads/2018/01/stage.png)](https://vlaim.s3.amazonaws.com/uploads/2018/01/stage.png) А так в staging’e

**Теперь мы с одного взгляда можем отличить в каком окружении находимся и вряд ли запутаемся.**

Реализация максимально проста. Например, в PHP это делается так:

<pre id="8e16" class="graf graf--pre graf-after--p">$color = '#'.substr(md5($_SERVER[‘HTTP_HOST’]),0,6);</pre>

В JS, к сожалению, нет методов генерации хеша из коробки, [но можно реализовать их](https://stackoverflow.com/questions/3426404/create-a-hexadecimal-colour-based-on-a-string-with-javascript), либо воспользоваться [отдельным модул](https://www.npmjs.com/package/string-to-color)ем для получения цвета из строки.

Теперь цвет из этой переменной остается применить к панели.

Успехов!

</div>

</figure>