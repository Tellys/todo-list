<template>
    <span :style="btnStyles">
        <slot></slot>
    </span>
</template>

<script>
export default {
    name: 'IconFileSvg',
    props: {
        bgColor: { type: String, default: process.env.VUE_APP_ICONS_DEFAULT_BG_COLOR },
        height: { type: String, default: '100px' },
        width: { type: String, default: '100px' },
        icon: { type: String },
        iconUrl: { type: String, default: `${process.env.VUE_APP_ICONS_URL_ASSETS}` },
    },
    computed: {
        btnStyles() {
            return {
                display: 'inline-block',
                'vertical-align': 'middle',
                height: `${this.height}`,
                width: `${this.width}`,
                'padding': '1px',
                "background-color": this.bgColor,
                //'background-image': 'url(https://images.unsplash.com/photo-1528287942171-fbe365d1d9ac?ixlib=rb-1.2.1&q=85&fm=jpg&crop=entropy&w=1200&cs=srgb&ixid=eyJhcHBfaWQiOjE0NTg5fQ)',
                //'background-size': 'contain',
                // 'background-position': 'center',
                // 'background-repeat': 'no-repeat',
                //'mask-image': "url(" + require(this.iconUrl+this.icon+`.svg`) + ")",
                'mask-image': "url(" + require(`@/assets/icons/${this.icon}.svg`) + ")",
                'mask-size': '95%',
                'mask-repeat': 'no-repeat',
                'mask-position': 'center',
                'mask-composite': 'add',
            };
        }
    }
};
</script>