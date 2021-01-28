# 关于ios填充整个屏幕问题
## 填充整个手机屏幕
### ios 新增 viweport-fit 属性，使得页面内容完全覆盖整个窗口：
```
<meta name="viewport" content="width=device-width, viewport-fit=cover">
```
## constant 函数,后面会使用 env函数,一般两个都写一遍
### OS11 新增特性，Webkit 的一个 CSS 函数，用于设定安全区域与边界的距离，有四个预定义的变量（单位是px）：
* safe-area-inset-left：安全区域距离左边界距离
* safe-area-inset-right：安全区域距离右边界距离
* safe-area-inset-top：安全区域距离顶部边界距离
* safe-area-inset-bottom：安全区域距离底部边界距离
## 官方文档中提到将来 env()要替换 constant ()，目前还不可用
### 这两个函数都是 webkit 中 css 函数，可以直接使用变量函数，只有在 webkit 内核下才支持
* constant：针对iOS < 11.2以下系统
* env：针对于iOS >= 11.2的系统

### 注意：网页默认不添加扩展的表现是 viewport-fit=contain，需要适配 iPhoneX 必须设置 viewport-fit=cover，不然 constant 函数是不起作用的，这是适配的必要条件。

# fixed 元素的适配
## 1,fixed 完全吸底元素（bottom = 0），比如下图这两种情况
### 可以通过加内边距 padding 扩展高度：
```
{
    padding-bottom: constant(safe-area-inset-bottom);
}
```
### 或者通过计算函数 calc 覆盖原来高度：
```
{
    height: calc(60px(假设值) + constant(safe-area-inset-bottom));
}
```
### 还有一种方案就是，可以通过新增一个新的元素（空的颜色块，主要用于小黑条高度的占位），然后吸底元素可以不改变高度只需要调整位置，像这样：
```
{
    margin-bottom: constant(safe-area-inset-bottom);
}
```
## 2,fixed 非完全吸底元素（bottom ≠ 0），比如 “返回顶部”、“侧边广告” 等
### 像这种只是位置需要对应向上调整，可以仅通过外边距 margin 来处理：
```
{
    margin-bottom: constant(safe-area-inset-bottom);
}
```
### 或者，你也可以通过计算函数 calc 覆盖原来 bottom 值：
```
{
    bottom: calc(50px(假设值) + constant(safe-area-inset-bottom));
}
```
### 一般我们只希望 iPhoneX 才需要新增适配样式,我们可以配合 @supports 这样编写样式：
```
@supports (bottom: constant(safe-area-inset-bottom)) {
    div {
        margin-bottom: constant(safe-area-inset-bottom);
    }
}
```