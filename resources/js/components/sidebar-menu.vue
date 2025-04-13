<template>
    <div class="main-nav">
        <ul class="m-0 list-unstyled">
            <li class="has-dropdown active" v-if="role == 'Admin'">
                <a href="javascript:void(0)"><i class="icon-user"></i> <span>Tasks</span></a>
                <div class="submenu">
                    <ul>
                        <li><router-link :to="{name: 'add-task'}">Add</router-link></li>
                        <li><router-link :to="{name: 'list-task'}">Manage</router-link></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
    import $ from 'jquery';
    import { onMounted, inject } from 'vue';
    import { useRoute, useRouter } from 'vue-router';

    const store = inject('store');
    const router = useRouter();
    const route = useRoute();

    const role = store.getters.user?.role;

    router.beforeEach((to, from) => {
        $('.main-nav>ul li a').each(function() {
            if(to.href == $(this).attr('href')){
                $(this).parents('.submenu').parent().siblings().find('.submenu li.active').removeClass('active')
            }
        })
    })

    onMounted(() => {
        // Add arrow in submenu parent li
        $('.submenu').parent().append('<span class="expend"></span>')

        // Toggle Submenu
        $(document).off('click', '.main-nav ul>li>a').on('click', '.main-nav ul>li>a', function() {
            $(this).parent().toggleClass('active').siblings().removeClass('active').find(".submenu").stop().slideUp(300)
            $(this).parent().find(".submenu").stop().slideToggle(300)

            let multiMenu = $(this).parent().find('.multi-level-menu')
            let multiMenuLi = $(this).parent().find('.multi-level-menu li')
            let multiMenuParent = $(this).parent().find('.multi-level-menu li.active').parents('li:not(.has-dropdown)')

            if($(this).parent().find('.submenu:not(.multi-level-menu)').parent().hasClass('active')){
                multiMenu.hide()

                if(multiMenuLi.hasClass('active') && multiMenuLi.find('a').hasClass('router-link-active')){
                    multiMenuParent.addClass('active').siblings().removeClass('active')
                    multiMenuParent.find('.multi-level-menu').slideDown()
                }
                else if(!multiMenuLi.hasClass('active') && !multiMenuLi.find('a').hasClass('router-link-active')){
                    multiMenu.parent().removeClass('active')
                }
            }
            else{
                if(!multiMenu.parents('.submenu').parent().hasClass('active') && !multiMenu.parent().hasClass('active')){
                    multiMenu.hide()
                }
                if(!$(this).parents('.has-multi-dropdown').hasClass('active')){
                    $(this).parent().siblings().find('.multi-level-menu li.active').removeClass('active')
                }
            }
        })

        // Active menu li
        $('.main-nav>ul li a').each(function() {
            if($(this).attr('href') == route.path){
                let mainLi = $(this).parents('.main-nav>ul>li')
                mainLi.addClass('active').siblings().removeClass('active')

                $(this).parents('.has-multi-dropdown').addClass('active')
                $(this).parent().addClass('active')

                if(mainLi.hasClass('active')){
                    mainLi.find('.submenu').show()
                    mainLi.find('.multi-level-menu').parent().not('.active').find('.submenu').hide()
                }
            }
        })
    })

</script>
