<style>
    .footer-wrapper .footer-title p {
        color: #EB174F;
        font-size: 12px;
        margin-top: 26px;
    }

    .footer-nav-item span {
        font-size: 16px;
        font-weight: 700;
        color: #EB174F;
        margin-bottom: 37px;
    }

    .footer-wrapper .footer-title .sub {
        width: 156px;
        height: 42px;
        color: #EB174F;
        line-height: 42px;
        font-size: 12px;
        text-align: center;
        border: 1px solid #EB174F;
        border-radius: 4px;
        cursor: pointer;
        will-change: filter;
        transition: filter 1.3s;
    }

    .footer-wrapper .footer-title .sub:hover {
        background-color: #EB174F;
        color: #fff;
    }
    .footer-nav-item ul li:hover {
        color: #EB174F;
    }
    ul {
        padding: 0px;
    }
    .el-button--primary:focus, .el-button--primary:hover {
        background-color: #000;
        border-color: #000;
        color: #FFF;
    }
    /* Tooltip cho Contact us - bên trái, màu trắng */
    .contact-tooltip {
        position: relative;
        cursor: pointer;
        display: inline-block;
    }

    .contact-tooltip-content {
        visibility: hidden;
        width: max-content;
        background: white;
        color: #333;
        text-align: center;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 14px;
        position: absolute;
        bottom: 50%;
        right: 125%;
        transform: translateY(50%);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s, visibility 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        border: 1px solid #ddd;
    }

    .contact-tooltip-content::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 100%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-left-color: white;
    }

    .contact-tooltip:hover .contact-tooltip-content {
        visibility: visible;
        opacity: 1;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .contact-tooltip-content {
            font-size: 12px;
            padding: 6px 10px;
            right: 110%;
        }
    }
</style>



<div data-v-6684c942="" class="footer">
    <div class="app-container">
        <div class="footer-wrapper flex-start">
            <div class="footer-title flex-start">
                <img src="{{ asset('filemanager/userfiles/admin/shoplogo.247e230e.svg') }}" alt="Ảnh" style="height: 40px;">
                <p>{{__('lang.Get_More_Coupons')}}</p>
                <div class="el-input">
                    <!----><input type="text" autocomplete="off" placeholder="{{__('lang.Your_email_address')}}"
                                  class="el-input__inner" /><!----><!----><!----><!---->
                </div>
                <div class="sub">{{__('lang.Subcribe')}}</div>
            </div>
            <div class="footer-nav">
                <div class="footer-nav-item">
                    <span>{{__('lang.Customer_service')}}</span>
                    <ul>
                        <li class="el-tooltip openChatIcon" aria-describedby="el-tooltip-5179" tabindex="0">
                            {{__('lang.Online_Customer_service')}}
                        </li>
                        <li class="el-tooltip contact-tooltip" aria-describedby="el-tooltip-7568" tabindex="0">
                            {{__('lang.Contact_us')}}
                            <span class="contact-tooltip-content">Shopify@Shopify.com</span>
                        </li>
                    </ul>
                </div>
                <div class="footer-nav-item">
                    <span>{{__('lang.Returns_and_Exchanges')}}</span>
                    <ul>
                          <li class="el-tooltip" aria-describedby="el-tooltip-6624" tabindex="0">
                              <a href="{{route('posts.index','privacy-policy')}}">
                                  {{__('lang.Privacy_Policy')}}

                              </a>
                          </li>
                          <li class="el-tooltip" aria-describedby="el-tooltip-2521" tabindex="0">
                              <a href="{{route('posts.index','return-policy')}}">
                                  {{__('lang.Return_Policy')}}

                              </a>
                          </li>
                          <li class="el-tooltip" aria-describedby="el-tooltip-754" tabindex="0">
                              <a href="{{route('posts.index','delivery-collection')}}">
                                  {{__('lang.Delivery_&_collection')}}
                              </a>
                          </li>
                          <li class="el-tooltip" aria-describedby="el-tooltip-8455" tabindex="0">
                              <a href="{{route('posts.index','seller-policy')}}">
                                  {{__("lang.Seller's_Policy")}}

                              </a>
                          </li>
                    </ul>
                </div>
                <div class="footer-nav-item">
                    <span>{{__('lang.User_Center')}}</span>
                    <ul>
                        <a href="{{url('/register-form')}}">
                            <li class="el-tooltip" aria-describedby="el-tooltip-8558" tabindex="0">
                                {{__('lang.User_Registration')}}
                            </li>
                        </a>
                        <a href="{{url('/profile-user')}}">
                            <li class="el-tooltip" aria-describedby="el-tooltip-9696" tabindex="0">
                                {{__('lang.Order_Inquiry')}}
                            </li>
                            <li class="el-tooltip" aria-describedby="el-tooltip-8242" tabindex="0">
                                {{__('lang.Favorite_Products')}}
                            </li>
                            <li class="el-tooltip" aria-describedby="el-tooltip-8121" tabindex="0">
                                {{__('lang.My_Wallet')}}
                            </li>
                        </a>


                    </ul>
                </div>
                <div class="footer-nav-item">
                    <span>{{__('lang.About_us')}}</span>
                    <ul>
                            <li class="el-tooltip" aria-describedby="el-tooltip-8719" tabindex="0">
                                <a href="{{route('posts.index','about-us')}}">
                                    {{__('lang.About_us')}}
                                </a>
                            </li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-wrapper">
            <div class="flex-start sec">
                <div class="payment">
                    <div class="title-f">{{__('lang.Payment_Methods')}}</div>
                    <div class="payment-methods">
                        <div class="pay">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAABgFBMVEVHcEzwuQvxuAvxugvwuQrwuQvvtwvvtwjvrxDwugvvvxDvuAzvugvxuQvwuQvvtwvwugvxuwvvuAvvtQvvtwrvtgv6+vrwuQvwuQ/wuQr58tvxwSfwuhDxwCLywSX589zwuQ7246zxwSrxwSn589758dv6+/v69/PwuhX6+PX6+vv00mfxvyLxwCv58+T58uDxwCjywSfxwCT58tzxwCn58t3xwCf58dj58+L5+PTywCj236L2467003D01HH59/P00m358NjxwTLywCvywCr58tnywCfxwS7xwjT58t/58d724qz13ZT58t758dz0z1/xwCb5893246v01G/zzFzz0WL014/01Yb124v01GvxwTPywCP236bzzl/58+DwuRL24arwuhfwuhTzzmX34rDzzWH23qL00W3247D00Gr236X24af23KD24an24qrwuxj236n13KD236PwuQn24KX24qfwuArwuRT24qj236H236T0z2bzzWD00GbyzWHzz2bGM/pIAAAAFnRSTlMAz5+f399gIBDvEJAwj++gz4+QMKCQP3dnLAAAAdNJREFUGBllwYV220AQQNExSCvZ4bbzJLPDzMxlZmZmZm5/vRtrT5Im98qWwE+nQghTGT+Q3UxzyJaMkf8FzezQHsg2JoszUsTxjGxqyuIUC1PjON4ecUwWZ7JbdWYSxzPSEGRxZk+o1T2B4wWyoRmnWNCGwiGcdrEMznS3OoUJHCMiGRLjPWotv1NrZpFEh0iORPG8Wu+j6JtaPYs0tATik7i8oDpYjyD6oKoDt0n4ksF5uKb1CKu/rr3XYxJpSWGVr9Xg6Y9++P0HopcrMdHVm1htEgLnevTWXYhhXfvWIYboglYrQIsAxYKqLtWAX2uqOgT0L6lq4SAgwMgZVX3yABgaVB34CZTuqerUEUBCoOuOnozgI/HKQO8qfIH7j3RsGmiRFFbXlYh49VNE/HcI3r5+DqVTs1htspdEfLFX33zFKj3TvscQsyEtPolLC6r6vQal5UHVvrMkfMmFNNw4rdbnWumVWtUKiUCkg8TRebVe1NWaK5NIi4jB6RxVp1rGMWLlcY4d14bDFZy8bMhlcTrn1ZobxvECaTBZnK5R1eowjmfEacriHBjbX8HxmmSTyeJ0lnE8I9vk8uyQD+R/ppUt4T4juwV+ayqEMJX2A9n0D3wHYD2qhHsbAAAAAElFTkSuQmCC"
                                 alt="" /><span>Binance</span>
                        </div>
                        <div class="pay">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAABUFBMVEVHcEz///////////////////////////////////////////////8cIUMqquApquCztcAtq+EbIEMaH0IeI0Vjwejb8fpow+mFz+6Slab09fdKt+Xt+PxtcIe0tMApqeB2yevv+P11yOvn5+qcnq52x+ooLU2mqLaho7K9vsiq3vNwc4nC5vbR0tg3O1ri5Oi3usWM0e6trbokKUr3+PgyOFc/Q2AbIEJ5fI9ydosyN1X1+/7e3+T6+vuprLksMVFUWHI7P1yw3/Ozs79na4Kk2vKfobEaIEI+suNgZH1Tuub9/f3n6Ozk9PtQVG4qLk/a3OFHteX6/P6RkqP4/P4vM1M9Ql+P0++kprNNUWzf8vr8/v/39/iSlKV5yey44vQwrOFLUGzg4eY0OFZlwund8fo0OVno9vyvsbyFzu3i4+hGteS95Pb19feIi51XW3Pe4nW7AAAADHRSTlMAYJCP75/P3yAQMKDK7SVNAAABaklEQVQ4y4WTVZeDMBCFAy0U2plU193dXbvu7u6u//9tk24hA+WczlMm3yVzw0wYc8PWDDMMEDYNzWaFYYUEdMOwfNgOgS9CnlOsCBREhBwSDeBCEQ36fjbgDJvwmZFBqvj3Qf1NZ0aHqdNcAVq3H1MJmssiBt2YQBynuS4c0LxvEnGOboRtptG8CxFPPHfVPBXa00Jw7xEYzFRJ25DgeA1HRGAy1aKOrOQvH3B7Skwwd9nUK3nmDeCJLyuFEtRIjj8AX3G+NqUETonKVsl/v+H1k3PeqUo4Jmslf3wAeBec1yuTsfyqQgoOAZ7vpKBEXdP5Uc2Cr+4B3EjOy9SPsvMmesYQjwEO9nOCckcgGq7nl/OIuwAXccnrSt0Kqt0rKUxvwXZSChppu90j1hHP4HJD8Kpq1W0ycptLeH4FC/FkS4N35NTQJnYWAQa6Cwe/2NgXfzjCh+7nuv8BWzE6iroV8L5tLRbw/P8At705qs0HHhcAAAAASUVORK5CYII="
                                 alt="" /><span>Huobi</span>
                        </div>
                        <div class="pay">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAgVBMVEVHcEwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD///8AAADZ2dns7Ozz8/Pr6+vc3NyysrJVVVVvb29ZWVl3d3e/v7+1tbW+vr63t7efn5+rq6vLy8uvr6+8vLyZmZmmpqalpaXe3t7CwsLFxcWzs7OioqKkpKS0tLSYaMTaAAAADHRSTlMAYJCP75/PECDfMKA7W4s6AAAA4ElEQVQ4y42T1xLEIAhF1Wg0hWzvvZf//8AV3Mm4Kbr3JUM4AgowVktzlUoAmSquWVsmsc5ayjTcOoGGkp8oJoOWMi9I3uG3RB4678fQ1n8boY7OcSGjsoSrA+urStTYARMyhlgpJYB+ADCJCgHCVgAhQGrGgwBwlwHOa9R7TnouUTv35iz1Lj6lo+XC+5UyGQYkgzAAcSCaIlpkQd/TBvWYkV4r1OF7zehDaRkEbMNFCFD/tJtC9ACiHrn7ALV3wJaMaz1y0aGNj318cWwdoukXzQU2hd8iYTr2W/OiY/0/ypQsB0nGeUwAAAAASUVORK5CYII="
                                 alt="" /><span>OKX</span>
                        </div>
                        <div class="pay">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAACslBMVEVHcEwehLUghrkef7AggK8efawghbcggK8ggLcghbggcJ8cfKsgh7cghbchh7oghroehLYdgrQdfq8ce6sdfKoegbEfgbIceqcgeKcggKcefawgeqoefa0ghbgghbUde6kfgbMgh7oegLAghboQgK8fgbEgh7gghrkggrIegbMfg7Ude6wef68ee6sefq4efKt7ss59tM4ghbj///8efq4fg7UefKsfgbIegLAghLcef6/6/P0ef7AfgLEde6ofhLYfgrQefq0efawdgLEefa0cfq85jrhvq8l8s86Et9Gjyd292OZ9tNB7s9F7ss8khbUehLccgrYghbceg7XH3+u+2+n+/v4kiboig7MdfKoegLEdfq31+fvn8vaozd+Qv9U5kLrd6/Lk7/VSm7/j7vS92ec9kbw8kLlVncCfyNxgo8Wt0OE3jLZ/tc+WwdcphLAwiLNkpsYdfKwwh7KBuNE4jLYzibOy0eFPmb5eosMshbF3sMy71+XP4ux8s9C71+ZlpschgrJWocfH3+udxtyo0OOw0ePL4ux4tNIzjr1Km8Rwr8+w0+WCuteZxt0xjLsmh7dzsM9jp8nn8vcdgbSw0+NjqMvg7vUff7G21uYaf7D3+vyAtdH7/f5zr83B2+gefatyrMoriLaSv9ZJlbxjo8Th7vVGlLyQv9YaeakqhbKuzd4shbNQmr81jbm91+UngK43irQ9kLpcocNgosM/kLiUwtiy0uJYn8KOvdVBkLhRmr9Xm7/p8vd6ss6rzd8jga9PmL0qhLGgyNujyNsuhbFNlrzR5O3D2+d4sc2RvdQcfa1Nmb240+Jbn8Eafa00i7Ygfqw1i7Rdn8G+2OaWwtm82OagxtuZwtg8jbVnpsUxibUhf6631eSOvdSOutI4jbebxdqDtdCv0OLN4eyqzt/xqNzVAAAAMnRSTlMAn4+fIN/fECDfEJAgYO/Pj2Bgz+/P72AgIJAw358woM+fzzAQ75/v75Dvn++gkO/f39Ah0z8AAAJxSURBVDjLfZP1VxRRFMcfGyzL0iFpi53ckRlnZ9QN3TCoRUBBREDpbgTBY3d3d3d3d3e3/4f37bICyx4/P9x5734/82bOee8R8g+FZ09vr8REL++ungrSHg9Jr8gWOro5xApJpAOSNqt4KO39uDj7SNlqke62PGbZkjkJCYvnL4ixGZ3suZtyGGWWZQLYmLfQ2nBtXkPhSmexs+0xZWms1bD9h88IJNoCbbBE066P9QNDkfjJ4MDceNqXoeBCByugHYtovw/+AX2uXtdeWLUcg74K4kuFteCElTTxJS4jkQ3OhDU0cSFdWJbVpQNs2baXdut/05qapQbI1GEUSHpj3bgJYKvu6BWAP01NvzDaqdsMkLIeow4kCSuH/jSWfQ0wlmXHAWSx7GgA9USMWJIkiqJVEMWZKIhiA8BbUZxCBYxYEsFxnIDCSY5DoYHjxgOM4Ti6Ao9RBAnEOgmF6Rw3w0FgMOpGpIIgWAWBCkZBoIIgWAWMpMSP53kmFdQHeR73o5HnGwGu8fzhFMgchZEfUfRnGOZIziWsxWevPmOY68Y3n3ByKmcfVqYHIQOZ/yDF3ZRpNJrsRyU3L5cUGY35Zyryaz7XGb8/rzHlYkC3m4RqtLcf3ii7d7HgRMZ+Q92X4p8fK6uqn7yo12pUtiMXXjr1flFZuf504aFdhvR3+rxvX82PTfqq0vDmoy+Tm+/eulOuP3ZgT5oh98Kr9x9qC6tN2nNymf1Yhw3RPqg4r83O25GWsft4Qe0Pc+XLp9rBYS0XI0QeRdke1Qp5SJurFxrlgMrd4XYGDxjewiBVsJP77R7U2T8gOTnAv19Qq7f/Ap2K7MK9VrI9AAAAAElFTkSuQmCC"
                                 alt="" /><span>KraKen</span>
                        </div>
                        <div class="pay">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAA/FBMVEVHcEwAUf0AUv8AUPoAUv8AUP8AUPcAUv4AUP8AUv4AUf8AUPwAUvwAUf0AUf4AU/4AUP8AUv7///8AUf4AUP4WX/42df4KWP42dP4DU/7f6P/o7/+3zf9gkf6qw//k6//5+/9hkv5Bff6vxv9unP6AqP9Cff5AfP7g6P+owv+Ap/5llf/f5/+Nr//g5/9fkP6lwP6Orv4+e/6Mr/+qw/5gkP7l7P+0yv9hkv/3+P8/e/7e5v9llf6Lrv6lwP/p7//4+f/3+v8DUv62zP+Nrv6Jrf+Jrf4XYP73+f9tnP9nlv6Krv9/qP5unf6mwP4ycv5mlv4KWf4CUv60y/9Ujt05AAAAEXRSTlMAkI8wnyAg3xDvz2BgoM/PYEBfP20AAAFrSURBVDjLhVPXdsIwDHVCAIcAbRYpEAgbuvfee+///5fakk0dk3O4L5HuVSzJkgmZgjpWyXZdu2SVKZlF3mTiFFZBk6npajBTp+SL7gyKyiG5DJ1F5DL+94Ng9gwq9Wj1MHnqTOLujYzAOmR9z01PYO1IVgoJhHPvKVgXJE9iobnrpfCKrMEqQGvT0/AOtE2JA0a9AexWLwx32mA2IxDKIsM+kCs+tPoGzhfeOVmA7y2n9nxxGR/c+wW7RHBEp5wayxsacS+BcJsAE1xx6lEGfNcYPtEWAcc8IJQBP0sMNUwoUiQ8YCQDxtybYJ+iyD6nlmWR0GgsiqzA9ww6G2KbQ3Baos1FHCQOqn0Shr0LMBt1cVEUixjoV93FdGzgBloPaX1bLK8y7jtVP/f/xy2PcFsNKV8O3Om01ZWrt+KNzvVB/yVKr5y2tH7G4s9b+/kPh9Vh6LqhP+B8RVFto5DxvqlTqfLnX7Uc5e8/QGw/3vnF7T4AAAAASUVORK5CYII="
                                 alt="" /><span>Coinbase</span>
                        </div>
                        <div class="pay">
                            <img src="data:image/png;base64,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"
                                 alt="" /><span>MetaMask</span>
                        </div>
                        <div class="pay">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAABelBMVEVHcEwTrowQr48Qr4AQp4cSrowTrYoSro0Qr4cTro0Qn4AQn48TrowTro0SrowSro0Ur40Sr4wTrY0Qr4oTrosTr40Tr4wTrY0UrowSr40VqooQqooSrosTrooSroz///8Troyq4dXf9O/9/v76/v4SrowTrYvZ8u0uuJl50b8vuJn8/v4utpjg9O8Wr40RrYsWrox60b83u5540b45u56b3M6a3M7Z8ezY8exIv6Z30b6E1cSF1cQ4up4ktJQvuJr8///9/v+j39NLw6nu+Pdgx7B70b9Lwaii39JSw6tszbkut5j0+vkyuZvy+vgYr46t49ea286a3M3b8+0vtpi25tuM18Y5u584uZ04u58Xro38//44u56R2coSrYva8uw2u56f3dAut5c5uJ3f8+7g8+/Y8uwyuJqd3M86vKBHwKeM1sWc3M4rtZYRrYrZ8+0csZEtuJliybOC08Ht+fYjs5Tn9PFhx7FrzLeJ1sW35Nsjs5Nmy7a96uGiDhWoAAAAH3RSTlMAkBAQIM9g3yDfEBDv7+/vj59gMKCfn5/PjzAwoJCQn3J3pwAAAbdJREFUOMttk1VjGlEQhQffQAiBSJPa+bKbLRRSoBKppu7u7u7u/e992F12Iczr+c6duSNmvSjWqjNlKM9M5Yu2NrLjZeKYyg7IxVkGYrzUZx9hTYwkHtkwRIeR9cP9B/buHnij2KcvuPL2RERQxyxw+/K1WwCccSW1dkaVmpllAe5JVzqBX5JaW0Mia2brAOak+S3w2JXkSfJCImWWJgZ2bZNUf/dWknccgNGS5WMg0Jt0lyQdPA1APsjAnDT/JNShuyjpJABVy0XAnWeS6g0Aum909AQAORuLAAW6//f3rw7vX58Lqhw1EsBKA/58l34mGtcH1Js+375Kn8H3I6AvxYeGz49/Xz76nVcLUYpeka2nklY+Afjtlzp7KixyY++bN11Jyw3w24uSDgMwbbW4UTdcSctN2kuSDh0LG5Uei1t915X0/MUlSV440JLZ5sSwHrqSzieGNW1mDsB16cF9YEcw7kjHMTNLARceXbwKEeFtpzdtM0sXkiu33431QiZYSqePWN13JNKdaK0nCsPWvjARH4YzhIj9Zmbp1KCeygxcpzOZUCubnCH3nclP5ipQyVVrCfd/gep1dHDAexMAAAAASUVORK5CYII="
                                 alt="" /><span>KuCoin</span>
                        </div>
                        <div class="pay">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAMAAACdt4HsAAACUlBMVEVHcEz///////////////////////////////////////////////////////////////////////////////8tRpIuR5OXpMna3uyzvdhidK35+vwvSJNhdK39/f5QZKRcb6rGzOH7+/329/v8/f29xd3b3+w6UZkySpUwSJMxSpRecqz+/v4zS5X+/v/6+/07Upn6+/zx8vieqs01TZbi5fAuRpIuR5KsttTd4e04UJg0TJVld6/w8vcwSZSIlsG2vtnd4u2qtdPFzOGps9Ktt9Tk6PGrtdScqMzx8vcyS5TIz+Pn6vOuuNXV2uh+jbze4u41Tpc8VJm3wNrN0uXm6fIwSZOrtdP09fnY3eu4wdo9VJpNYqLS1+iYpMpsfbKirc+Xo8k4UJc8U5qYpMnv8fbN0+VoerF7i7pwgbR/jr3S1+f19vrq7PRGXJ9PZKPv8fdugLTr7fXu8PZAVpx1hbhCWJ3o6vPn6fLDy+DHzuJyg7Vhda2Uocg0TZVfcqz8/P03T5fc4e35+fvT1+iGlcE3Tpfb3+2QncWBkL67w9xVaab19vn3+PsySpTp6/NnebC8xdyyu9e+xd7b4OzR1+dDWZ5IXaCCkb5kd688VJpBV5xJX6FBV515ibrn6vKqs9NLYKKeqc1RZqWVosg0TJY6UpnT2OjL0uTz9fnm6vPAx97l6PHX3Ore4e1OY6Pi5vCJl8L19/prfLLJz+RrfLFmd69uf7RRZqQzTJX4+PtLYaGwudbW2+rY3Ot0hLdCWJySn8fGzOJoerDEy+DDyuDKDGouAAAAFHRSTlMAkHAQz4Bg3+8gQJ+Pr6C/f1BvsFc6sn4AAAL+SURBVFjDrVdlg9pQEAwHIQl6tLsk7XHQ0nN3v17d3d3d3d3d3d3d3fu/+khSIBxwJO/m00Rm3j7JZpdhEkCwOpwWDgg4i5P1CIwu2ByyNBqc3Zaq2pzOQ1xwVoFGHgLvaNOiQxK5EkVSueCGNsEJxodX55GWSO+AFJEeX2+HlGGPp7eADlioxo8bQzrohCNm/UE3NHsh8PoN+OjzwIEBuCN6KxhCeBICZ8yAN+s9gbFgja+gJgQrAF0InHEDXs5/QAGb/o9ACxPdDEh6InsAVBAYD52BlWHpDEyM8z/tKRIMk+mEGYT2UG5PnCaK/UIk2EsU+5aHWLYo9vGrqo6RTNbJi4hNMj3kI7SzTEu3EDpcpr3LsHYAEY4eiQ2DI7mN0xj49hD26i2GDU4XEFo8Qub9B2HeJBgzCvOGRm0DRBl89OEJwt74sEE1OHIbu19AnK68Mr4RZ82cjOPq/JFF0BgUfsCap1D6HRtbVIMb+Xjt4T2smSq/UlQZisdbmQUJDDLravF10ddi/P1XMXh0E+/eh1uI8xVN+SJisCAICQ2aP6Pv20/M+9NVMbjjw4vXm6v3Ydm80Cv+7HXEIHejxiB6ETO7fUF8/wObShWDF5cRLwUCgSrEhbvJDLLPY1VhMa7ZlBW1iBaNQcZzMkT3XyAblLxEvNolhFzMPQlw9hweO7BqbT4uWxz1i3JqDOAUMXgXVAyeXcFtDzJCmIO4/eiZABbsXQLrV3pxeT2ED5JJaxAM4M7HIBv4W7y4eYf8rGI25m89jN6DK8jFhkLEKWPDOSmc0ObmSE9KAIZIORkAnySpvuS4JFWoD6slaZck7V8tXyzNkaSB6gMP/edMnVAii2AE9vZIqgxPNwOGJicpRaPZ+K9NLRFYmr8CTQiRmjWNZgVkuI1vQTsVWUYmEVNy6z7QbGyp6tKnd7V/sa0rBlf8hoHVewJbIY2y5UmtZnUn7/ysnPHh1SBMyRpP1pxC7yokiiI1udJ8u1o33yabvgZe8LCR9t+UuO3+BzYlPZPOa6/2AAAAAElFTkSuQmCC"
                                 alt="" /><span>Bitfinex</span>
                        </div>
                    </div>
                </div>
                <div class="argos">
                    <div class="left">
                        <div class="title">TikTok-Wholesale</div>
                        <div class="dec">
                            {{__('lang.TikTok-Wholesale_global_site_users_come_from_112_countries_around_the_world_and_use_USDT/ETH/BTC_for_settlements._USDT/ETH/BTC_is_a_borderless_transaction_that_enables_immediate,_low-cost_transactions_around_the_world_without_waiting_and_charging_international_fees.')}}
                        </div>
                    </div>
                    <!---->
                </div>
            </div>
            <!---->
        </div>
        <div class="footer-bottom">
            <p style="text-align: center; margin-bottom: 15px">
                {{__('lang.TikTok-Wholesale_Limited_2023._All_Rights_Reserved.')}}
            </p>
            <p style="text-align: center">
                {{__("lang.Shopify_is_headquartered_in_Ottawa,_151_O'Connor_Street,_Ground_Floor,_Canada,_and_has_6_office_locations.")}}
            </p>
        </div>
    </div>
    <!---->
</div>
<script>
    var IMG_URL = '{{ CommonHelper::getUrlImageThumb('') }}'+'/';
</script>
<script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/cart.js') }}"></script>
