<template>
    <div>
        <div v-if="carried">
            <img class="w-100 custom-h-100-vh" :src="sauce+'/img/nu.jpg'" />
        </div>
        <div v-else class="custom-h-100-vh">
            <div id="err-box">
                <h1 id="err-header">404</h1>
            </div>
        </div>
    </div>
</template>

<style scoped>
* {
    transition: none;
}
#err-box {
    display: block;
    position: absolute;
    z-index: -1;
}
#err-header {
    font-size: 9rem;
}
</style>

<script>
export default {
    data: () => ({
        sauce: process.env.MIX_APP_URL,
        carried: false
    }),
    mounted() {
        this.carried =
            Math.floor(Math.random() * 50 + 1) >
            Math.floor(Math.random() * 50 + 1);

        if (!this.carried) {
            $("body").css("background-color", "#202124");
            $("#err-header").css(
                "color",
                `hsl(${Math.floor(Math.random() * 360)}, 80%, 50%)`
            );
            $("#err-box").css({
                top: `${Math.floor(Math.random() * 90 + 1)}%`,
                left: `${Math.floor(Math.random() * 80 + 1)}%`
            });

            (function($, window, undefined) {
                $.fn.marqueeify = function(options) {
                    var settings = $.extend(
                        {
                            horizontal: true,
                            vertical: true,
                            speed: 100,
                            container: $(this).parent(),
                            bumpEdge: function() {}
                        },
                        options
                    );
                    return this.each(function() {
                        var containerWidth,
                            containerHeight,
                            elWidth,
                            elHeight,
                            move,
                            getSizes,
                            $el = $(this);
                        getSizes = function() {
                            containerWidth = settings.container.outerWidth();
                            containerHeight = settings.container.outerHeight();
                            elWidth = $el.outerWidth();
                            elHeight = $el.outerHeight();
                        };
                        move = {
                            right: function() {
                                $el.animate(
                                    {
                                        left: containerWidth - elWidth
                                    },
                                    {
                                        duration:
                                            (containerWidth / settings.speed) *
                                            1000,
                                        queue: false,
                                        easing: "linear",
                                        complete: function() {
                                            settings.bumpEdge();
                                            move.left();
                                        }
                                    }
                                );
                            },
                            left: function() {
                                $el.animate(
                                    {
                                        left: 0
                                    },
                                    {
                                        duration:
                                            (containerWidth / settings.speed) *
                                            1000,
                                        queue: false,
                                        easing: "linear",
                                        complete: function() {
                                            settings.bumpEdge();
                                            move.right();
                                        }
                                    }
                                );
                            },
                            down: function() {
                                $el.animate(
                                    {
                                        top: containerHeight - elHeight
                                    },
                                    {
                                        duration:
                                            (containerHeight / settings.speed) *
                                            1000,
                                        queue: false,
                                        easing: "linear",
                                        complete: function() {
                                            settings.bumpEdge();
                                            move.up();
                                        }
                                    }
                                );
                            },
                            up: function() {
                                $el.animate(
                                    {
                                        top: 0
                                    },
                                    {
                                        duration:
                                            (containerHeight / settings.speed) *
                                            1000,
                                        queue: false,
                                        easing: "linear",
                                        complete: function() {
                                            settings.bumpEdge();
                                            move.down();
                                        }
                                    }
                                );
                            }
                        };
                        getSizes();
                        if (settings.horizontal) move.right();
                        if (settings.vertical) move.down();
                        $(window).resize(function() {
                            getSizes();
                        });
                    });
                };
            })(jQuery, window);

            $("#err-box").marqueeify({
                bumpEdge: function() {
                    $("#err-header").css(
                        "color",
                        `hsl(${Math.floor(Math.random() * 360)}, 80%, 50%)`
                    );
                }
            });
        }
    },
    beforeRouteLeave(to, from, next) {
        $("body").css("background-color", "#f8fafc");
        next();
    }
};
</script>
