define([
    'jquery'
], function($) {
    "use strict";
    

    $.widget('Winkel_InstagramPost.instagramPostItem', {
      options: {
      },
      _create: function(e) { 
        const self = this
        
        this.element.click(function(e){
            e.preventDefault()
            self.openModal()
        })
      },
      openModal(){
        const options = this.options
        const modalWrapper = $('<div/>',{
            class: 'modal-post'
        })
        const postWrapper =$('<div/>',{
            class: 'post-wrapper'
        })
        const postHead = $('<div/>',{
            class: 'post-head'
        })
        const postImage = $('<a/>',{
            class: 'post-image-wrapper',
            attr:{
                href:options.url,
                target:'_blank'
            },
            html: $('<img />',{
                attr:{
                    src: options.img
                }
            })
        })
        const postBody = $('<div/>',{
            class: 'post-body'
        })
        const postCaption = $('<span/>',{
            html:options.caption
        })
        const modalClose = $('<a/>',{
            class: 'modal-close',
            click(){
                modalWrapper.remove()
            }
        })

        const profile = $('<a/>',{
            class: 'post-owner',
            attr:{
                href:`https://www.instagram.com/${options.owner}`,
                target:'_blank'
            }
        })
        const profilePic = $('<img/>',{
            class: 'post-owner--pic',
            attr:{src:options.profile_pic}
        })
        const profileUname = $('<span/>',{
            class: 'post-owner--uname',
            text:options.owner
        })

        const captionOwner = $('<a/>',{
            css:{
                marginRight:'5px',
                fontWeight:700
            },
            attr:{
                href:`https://www.instagram.com/${options.owner}`,
                target:'_blank'
            },
            html:options.owner
        })

        profile.append(profilePic)
        profile.append(profileUname)

        postHead.append(modalClose)
        postHead.append(profile)

        postBody.append(captionOwner)
        postBody.append(postCaption)

        postWrapper.append(postHead)
        postWrapper.append(postImage)
        postWrapper.append(postBody)
        modalWrapper.appendTo($('body'))
        modalWrapper.append(postWrapper)
        setTimeout(() => {
            postWrapper.addClass('show')
        }, 100);
        postWrapper.click(function(e){
            e.stopPropagation();
        })
        modalWrapper.click(function(e){
            e.stopPropagation();
            $(this).remove()
        })
      }

    });

    return $.Winkel_InstagramPost.instagramPostItem;
});