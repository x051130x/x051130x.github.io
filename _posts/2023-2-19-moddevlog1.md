---
layout: post
title: 记录 | mod 开发日志#1 立项&环境搭建
banner: img/moddevlogbanner1.jpg
---

算是我mc生涯的一个重要事件，所以决定写写dev日志什么的。

## 背景

从入mod坑之初就有过自己写mod的想法，但是并没有什么灵感且技术力也不达标所以并未付诸实践。

新学期润学校脑壳里不知道为啥就有了各种奇怪的想法，决定予以复现。

于是就有了现在的这个~~啥都没得的~~mod：

Sign Industry / 标牌工艺

计划为装饰向mod，主要添加各种现实中存在的道路交通指示牌，还原向。

## 前期准备

JDK 17

一个 IDE，此处使用 IntelliJ IDEA 2022.3.2 Community Edition

使用的插件：[Minecraft Development](https://plugins.jetbrains.com/plugin/8327-minecraft-development) （不是必装但是有这个会少很多工作量）

## 开发环境搭建

IDEA 创建新项目，可以看见左侧侧边栏由插件提供的 Minecraft 项目。

<a href="https://imgse.com/i/pSLbsQH"><img class="scalableimg" src="https://s1.ax1x.com/2023/02/19/pSLbsQH.md.png" alt="pSLbsQH.md.png" border="0"></a>

上方下拉框选择项目 SDK ，这里因为计划在 1.18.2 上跑所以选用 jdk17。

下方选择 fabric mod。

完成后到下一部分。

<a href="https://imgse.com/i/pSLbyyd"><img class="scalableimg" src="https://s1.ax1x.com/2023/02/19/pSLbyyd.md.png" alt="pSLbyyd.md.png" border="0"></a>

Groupid 此处填写了我 web 的域名倒写

Artifactid 即这个项目的名称，此处填写了此 mod 的命名空间id。

上述这两栏都只能存在小写字母及数字，不能出现大写字母/汉字一类。

Version 即mod版本。

Next ，转到此页面 ▽

<a href="https://imgse.com/i/pSLbrSe"><img class="scalableimg" src="https://s1.ax1x.com/2023/02/19/pSLbrSe.md.png" alt="pSLbrSe.md.png" border="0"></a>

Mod Name 填写自己的 mod 名称即可。

Minecraft Version 选择1.18.2，Yarn Version 与 Loader Version 选择最新版，Loom Version 选择不带 SNAPSHOT 的最新版。

勾选 Use Fabric API，版本选用最新。

下方 Description 等四栏是选填，按情况写即可。

设置完成，等待 IDLE 构建开发环境，在我设备上此过程会持续约 1-2h。

期间 IDLE 会完成开发环境准备并一并执行 genSource 命令以生成 mc 源代码。

等待出现 BUILD SUCCESSFUL 即构建完成。

&gt; END &lt;