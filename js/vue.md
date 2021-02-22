## vue 判断点击在弹窗之外
```js
mounted() {
    //给页面添加鼠标抬起事件
    this.mydocument.addEventListener('mouseup',(e) => {
        //获取弹窗对象
        // const userCon = this.mydocument.getElementById('vipDraw')
        let userCon = this.$refs.vipDraw
        //判断弹窗对象中是否包含点击对象
        if(userCon &&　!userCon.contains(e.target)) {
            //如果包含则跳转回之前的页面
            this.$emit("update:show",false);
        }

    })
}
```
## 自己写弹窗控件，可以通过遮罩层来点击取消弹窗