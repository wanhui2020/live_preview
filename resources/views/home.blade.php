<!DOCTYPE html>
<html class="full-height" lang="zh-CN">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="Material design app landing page template built"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="/home/css/bootstrap.min.css" rel="stylesheet">
    <link href="/home/css/mdb.min.css" rel="stylesheet">
    <link href="/home/styles/main.css" rel="stylesheet">
</head>
<body id="top">
<header>
    <!-- Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar" id="navbar">
        <div class="container"><a class="navbar-brand" href="#"><strong>{{ config('app.name') }}</strong></a>
            {{--            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"--}}
            {{--                    aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>--}}
            {{--            <div class="collapse navbar-collapse" id="navbarContent">--}}
            {{--                <ul class="navbar-nav ml-auto">--}}
            {{--                    <li class="nav-item"><a class="nav-link active" href="#features">Features</a></li>--}}
            {{--                    <li class="nav-item"><a class="nav-link active" href="#screenshots">Screenshots</a></li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>--}}
            {{--                    <li class="nav-item"><a class="nav-link active" href="#about">About</a></li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="#client">Client</a></li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>--}}
            {{--                </ul>--}}
            {{--                <a class="btn btn-default btn-rounded my-0" href="#">Download</a>--}}
            {{--            </div>--}}
        </div>
    </nav>
    <!-- Intro Section-->
    <section class="view" id="intro">
        <div class="full-bg-img d-flex align-items-center">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-md-10 col-lg-6 text-center text-md-left margins">
                        <div class="white-text">
                            <div class="wow fadeInRight" data-wow-delay="0.3s">
                                <h1 class="h1 h1-responsive">寻找视频行业的奋斗者</h1>
                                <div class="h6">
                                    每一个奋斗者都值得被尊敬，<br/>
                                    每一个奋斗者都值得被肯定。<br/>
                                    在{{ config('app.name') }}APP，<br/>
                                    发现你的价值，<br/>
                                    收获你应得的。
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="row no-gutters wow fadeInRight" data-wow-delay="0.3s">
                    <div class="col-lg-2 col-md-3 col-sm-12 mr-md-4 justify-content-center d-flex mb-3">
                        <a href="{{ $androidUrl }}">
                            <img class="img-fluid hoverable" src="/home/img/google-play.png" alt="google play logo">
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-12 justify-content-center d-flex">
                        <a href="https://www.apple.com.cn/ios/app-store/">
                            <img class="img-fluid hoverable" src="/home/img/app-store.png" alt="app store logo">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</header>
{{--<div id="container">--}}
{{--    <section class="py-5" id="features">--}}
{{--        <div class="container">--}}
{{--            <div class="wow fadeIn">--}}
{{--                <h2 class="h1-responsive h1 text-center my-5">Why is it so great?</h2>--}}
{{--                <p class="lead blue-grey-text text-center w-responsive mx-auto mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut--}}
{{--                    labore et dolore magna aliqua. Ut enim ad minim veniam.</p>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-6 wow fadeInLeft text-center text-lg-left"><img class="img-fluid" src="home/img/screens-section.png" alt="phone image"/></div>--}}
{{--                <div class="col-lg-6 pt-5">--}}
{{--                    <div class="row mb-3">--}}
{{--                        <div class="col-1 wow bounceIn"><i class="fa fa-mail-forward fa-lg indigo-text"></i></div>--}}
{{--                        <div class="col-xl-10 col-md-11 col-10 wow fadeInRight">--}}
{{--                            <h5 class="h4 mb-3">Safe</h5>--}}
{{--                            <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit enim ad minima veniam, quis nostrum exercitationem ullam. Reprehenderit--}}
{{--                                maiores aperiam assumenda deleniti hic.</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row mb-3">--}}
{{--                        <div class="col-1 wow bounceIn"><i class="fa fa-mail-forward fa-lg indigo-text"></i></div>--}}
{{--                        <div class="col-xl-10 col-md-11 col-10 wow fadeInRight">--}}
{{--                            <h5 class="h4 mb-3">Fast</h5>--}}
{{--                            <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit enim ad minima veniam, quis nostrum exercitationem ullam. Reprehenderit--}}
{{--                                maiores aperiam assumenda deleniti hic.</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-1 wow bounceIn"><i class="fa fa-mail-forward fa-lg indigo-text"></i></div>--}}
{{--                        <div class="col-xl-10 col-md-11 col-10 wow fadeInRight">--}}
{{--                            <h5 class="h4 mb-3">International</h5>--}}
{{--                            <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit enim ad minima veniam, quis nostrum exercitationem ullam. Reprehenderit--}}
{{--                                maiores aperiam assumenda deleniti hic.</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-1 wow bounceIn"><i class="fa fa-mail-forward fa-lg indigo-text"></i></div>--}}
{{--                        <div class="col-xl-10 col-md-11 col-10 wow fadeInRight">--}}
{{--                            <h5 class="h4 mb-3">Latest Technology</h5>--}}
{{--                            <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit enim ad minima veniam, quis nostrum exercitationem ullam. Reprehenderit--}}
{{--                                maiores aperiam assumenda deleniti hic.</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section class="py-5 grey lighten-4" id="screenshots">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="wow fadeIn">--}}
{{--                        <h2 class="h1 h1-responsive text-center text-md-right my-5"> Material app screenshots</h2>--}}
{{--                        <p class="lead blue-grey-text text-center text-md-right">Lorem ipsum dolor sit amet consectetur adipiscing elit sem fusce faucibus, rhoncus maecenas--}}
{{--                            pellentesque mattis praesent non velit a. Donec fringilla eros luctus parturient hendrerit posuere enim consequat tristique at sodales tempor, elementum--}}
{{--                            faucibus volutpat mattis neque fermentum fames vestibulum sagittis netus. </p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="wow fadeInUp">--}}
{{--                        <div class="pt-5">--}}
{{--                            <div class="carousel slide carousel-fade text-center" id="carousel-screenshot" data-ride="carousel">--}}
{{--                                <div class="carousel-inner text-center pb-5" role="listbox">--}}
{{--                                    <div class="carousel-item active"><img class="img-fluid z-depth-2" src="home/img/screenshot.jpg" alt="screenshot image"--}}
{{--                                                                           style="height:450px; width:auto;"/></div>--}}
{{--                                    <div class="carousel-item"><img class="img-fluid z-depth-2" src="home/img/screenshot-1.jpg" alt="screenshot image-1"--}}
{{--                                                                    style="height:450px; width:auto;"/></div>--}}
{{--                                    <div class="carousel-item"><img class="img-fluid z-depth-2" src="home/img/screenshot-2.jpg" alt="screenshot image-2"--}}
{{--                                                                    style="height:450px; width:auto;"/></div>--}}
{{--                                </div>--}}
{{--                                <a class="carousel-control-prev" href="#carousel-screenshot" role="button" data-slide="prev"><span aria-hidden="true"><i--}}
{{--                                            class="fa fa-arrow-left indigo-text "></i></span><span class="sr-only text-blue">Previous</span></a><a class="carousel-control-next"--}}
{{--                                                                                                                                                   href="#carousel-screenshot"--}}
{{--                                                                                                                                                   role="button"--}}
{{--                                                                                                                                                   data-slide="next"><span--}}
{{--                                        aria-hidden="true"><i class="fa fa-arrow-right indigo-text "></i></span><span class="sr-only">Next</span></a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section class="text-center" id="pricing">--}}
{{--        <div class="py-5">--}}
{{--            <div class="container">--}}
{{--                <div class="wow fadeIn">--}}
{{--                    <h2 class="h1 h1-responsive my-5">Our pricing plans</h2>--}}
{{--                    <p class="px-5 mb-5 pb-3 lead blue-grey-text">--}}
{{--                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit, error amet numquam iure provident voluptate esse quasi,--}}
{{--                        veritatis totam voluptas nostrum quisquam eum porro a pariatur accusamus veniam.--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--                <div class="row wow zoomIn">--}}
{{--                    <div class="col-lg-3 col-md-12 mb-4">--}}
{{--                        <div class="card card-image">--}}
{{--                            <div class="text-white text-center pricing-card d-flex align-items-center py-3 px-3 rounded indigo-text">--}}
{{--                                <div class="card-body">--}}
{{--                                    <div class="h5">Basic</div>--}}
{{--                                    <div class="py-4"><span class="display-4">Free</span></div>--}}
{{--                                    <ul class="list-unstyled">--}}
{{--                                        <li>--}}
{{--                                            <p><strong>1</strong> person</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>1</strong> projects</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>10</strong> features</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>5GB</strong> storage</p>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                    <a class="btn btn-outline-indigo mt-4"> Buy now</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-3 col-md-12 mb-4">--}}
{{--                        <div class="card card-image">--}}
{{--                            <div class="text-white text-center pricing-card d-flex align-items-center py-3 px-3 rounded indigo-text">--}}
{{--                                <div class="card-body">--}}
{{--                                    <div class="h5">Standard</div>--}}
{{--                                    <div class="py-4"><span class="display-4">$19</span><span class="display-5">/m</span></div>--}}
{{--                                    <ul class="list-unstyled">--}}
{{--                                        <li>--}}
{{--                                            <p><strong>10</strong> person</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>10</strong> projects</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>100</strong> features</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>20GB</strong> storage</p>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                    <a class="btn btn-outline-indigo mt-4"> Buy now</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-3 col-md-12 mb-4">--}}
{{--                        <div class="card card-image">--}}
{{--                            <div class="text-white text-center pricing-card d-flex align-items-center py-3 px-3 rounded indigo">--}}
{{--                                <div class="card-body">--}}
{{--                                    <div class="h5">Premium</div>--}}
{{--                                    <div class="py-4"><span class="display-4">$25</span><span class="display-5">/m</span></div>--}}
{{--                                    <ul class="list-unstyled">--}}
{{--                                        <li>--}}
{{--                                            <p><strong>20</strong> person</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>100</strong> projects</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>200</strong> features</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>100GB</strong> storage</p>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                    <a class="btn btn-outline-white mt-4"> Buy now</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-3 col-md-12 mb-4">--}}
{{--                        <div class="card card-image">--}}
{{--                            <div class="text-white text-center pricing-card d-flex align-items-center py-3 px-3 rounded indigo-text">--}}
{{--                                <div class="card-body">--}}
{{--                                    <div class="h5">Enterprise</div>--}}
{{--                                    <div class="py-4"><span class="display-4">$99</span><span class="display-5">/m</span></div>--}}
{{--                                    <ul class="list-unstyled">--}}
{{--                                        <li>--}}
{{--                                            <p><strong>20+</strong> person</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>Unlimited</strong> projects</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>Unlimited</strong> features</p>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <p><strong>1TB</strong> storage</p>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                    <a class="btn btn-outline-indigo mt-4"> Buy now</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <div class="tlinks">Collect from <a href="http://www.cssmoban.com/" title="网站模板">网站模板</a></div>--}}
{{--    <section class="py-5 grey lighten-4" id="about">--}}
{{--        <div class="container">--}}
{{--            <div class="wow fadeIn">--}}
{{--                <h2 class="h1 h1-responsive text-center my-5">About us</h2>--}}
{{--                <p class="lead blue-grey-text text-center w-responsive mx-auto mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut--}}
{{--                    labore et dolore magna aliqua. Ut enim ad minim veniam.</p>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-4 mb-md-0 mb-5 wow zoomIn" data-wow-delay=".1s">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-lg-2 col-md-3 col-2"><i class="fa fa-briefcase orange-text fa-2x"></i></div>--}}
{{--                        <div class="col-lg-10 col-md-9 col-10">--}}
{{--                            <h4 class="h4">Experience</h4>--}}
{{--                            <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim--}}
{{--                                ad minim veniam.</p><a class="btn btn-default btn-sm ml-0" href="#">Learn more</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 mb-md-0 mb-5 wow zoomIn" data-wow-delay=".3s">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-lg-2 col-md-3 col-2"><i class="fa fa-cogs indigo-text fa-2x"></i></div>--}}
{{--                        <div class="col-lg-10 col-md-9 col-10">--}}
{{--                            <h4 class="h4">Flexibility</h4>--}}
{{--                            <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim--}}
{{--                                ad minim veniam.</p><a class="btn btn-default btn-sm ml-0" href="#">Learn more</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 mb-md-0 mb-5 wow zoomIn" data-wow-delay=".5s">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-lg-2 col-md-3 col-2"><i class="fa fa-trophy green-text fa-2x"></i></div>--}}
{{--                        <div class="col-lg-10 col-md-9 col-10">--}}
{{--                            <h4 class="h4">Results </h4>--}}
{{--                            <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim--}}
{{--                                ad minim veniam.</p><a class="btn btn-default btn-sm ml-0" href="#">Learn more</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <img class="img-fluid mt-5 wow slideInUp" src="home/img/iphone.png" alt="iphone image"/>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section class="py-5" id="team">--}}
{{--        <div class="container">--}}
{{--            <div class="wow fadeIn">--}}
{{--                <h2 class="h1 h1-responsive text-center my-5">Our team members</h2>--}}
{{--                <p class="px-5 mb-5 pb-3 lead text-center blue-grey-text">--}}
{{--                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit, error amet numquam iure provident voluptate--}}
{{--                    esse quasi, veritatis totam voluptas nostrum quisquam eum porro a pariatur accusamus veniam.--}}
{{--                </p>--}}
{{--            </div>--}}
{{--            <div class="row mb-lg-4 text-center text-md-left">--}}
{{--                <div class="col-lg-6 col-md-12 mb-5 wow fadeInLeft" data-wow-delay=".3s">--}}
{{--                    <div class="col-md-6 float-left"><img class="img-fluid rounded z-depth-1 mb-3" src="home/img/woman-1.jpg" alt="team member"/></div>--}}
{{--                    <div class="col-md-6 float-right">--}}
{{--                        <div class="h4">Marie Crawford</div>--}}
{{--                        <h6 class="h6 blue-grey-text mb-3">Lead Designer</h6>--}}
{{--                        <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur.</p><a class="indigo-text" href="#"--}}
{{--                                                                                                                                               target="_blank"><i--}}
{{--                                class="fa fa-twitter"></i><span class="ml-1">@nicolewest</span></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-lg-6 col-md-12 mb-5 wow fadeInRight" data-wow-delay=".3s">--}}
{{--                    <div class="col-md-6 float-left"><img class="img-fluid rounded z-depth-1 mb-3" src="home/img/woman-2.jpg" alt="team member"/></div>--}}
{{--                    <div class="col-md-6 float-right">--}}
{{--                        <div class="h4">Debra Oliver</div>--}}
{{--                        <h6 class="h6 blue-grey-text mb-3">Photographer</h6>--}}
{{--                        <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur.</p><a class="indigo-text" href="#"--}}
{{--                                                                                                                                               target="_blank"><i--}}
{{--                                class="fa fa-twitter"></i><span class="ml-1">@hannahcruz</span></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row text-center text-md-left">--}}
{{--                <div class="col-lg-6 col-md-12 mb-5 wow fadeInLeft" data-wow-delay=".3s">--}}
{{--                    <div class="col-md-6 float-left"><img class="img-fluid rounded z-depth-1 mb-3" src="home/img/man-1.jpg" alt="team member"/></div>--}}
{{--                    <div class="col-md-6 float-right">--}}
{{--                        <div class="h4">Jesse Bell</div>--}}
{{--                        <h6 class="h6 blue-grey-text mb-3">Web Developer</h6>--}}
{{--                        <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur.</p><a class="indigo-text" href="#"--}}
{{--                                                                                                                                               target="_blank"><i--}}
{{--                                class="fa fa-twitter"></i><span class="ml-1">@markhall</span></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-lg-6 col-md-12 mb-5 wow fadeInRight" data-wow-delay=".3s">--}}
{{--                    <div class="col-md-6 float-left"><img class="img-fluid rounded z-depth-1 mb-3" src="home/img/man-2.jpg" alt="team member"/></div>--}}
{{--                    <div class="col-md-6 float-right">--}}
{{--                        <div class="h4">Wayne Ortega</div>--}}
{{--                        <h6 class="h6 blue-grey-text mb-3">Web Developer</h6>--}}
{{--                        <p class="grey-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur.</p><a class="indigo-text" href="#"--}}
{{--                                                                                                                                               target="_blank"><i--}}
{{--                                class="fa fa-twitter"></i><span class="ml-1">@vincentharris</span></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section class="py-5 grey lighten-4">--}}
{{--        <div class="container">--}}
{{--            <div class="wow fadeIn">--}}
{{--                <h2 class="h1 h1-responsive text-center my-5">Trusted by</h2>--}}
{{--                <p class="px-5 lead text-center blue-grey-text">--}}
{{--                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit, error amet numquam iure provident voluptate esse quasi,--}}
{{--                    veritatis totam voluptas nostrum quisquam eum porro a pariatur accusamus veniam.--}}
{{--                </p>--}}
{{--                <div class="row text-center mt-5 wow zoomIn">--}}
{{--                    <div class="col-md-3 col-sm-6 mb-4"><img class="img-fluid" src="home/img/logo-4.png" alt="company logo"/></div>--}}
{{--                    <div class="col-md-3 col-sm-6 mb-4"><img class="img-fluid" src="home/img/logo-2.png" alt="company logo 1"/></div>--}}
{{--                    <div class="col-md-3 col-sm-6 mb-4"><img class="img-fluid" src="home/img/logo-3.png" alt="company logo 2"/></div>--}}
{{--                    <div class="col-md-3 col-sm-6 mb-4"><img class="img-fluid" src="home/img/logo-1.png" alt="company logo 3"/></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section id="client">--}}
{{--        <div class="container py-5">--}}
{{--            <div class="wow fadeIn">--}}
{{--                <h2 class="h1 h1-responsive text-center my-5">What Our Customers Says</h2>--}}
{{--                <p class="px-5 mb-5 lead text-center blue-grey-text">Lorem ipsum dolor sit amet consectetur adipiscing elit pulvinar suscipit, at lectus duis conubia vulputate dis--}}
{{--                    egestas. Fermentum risus porta taciti ultricies venenatis viverra pulvinar, bibendum mi magnis potenti cum sociosqu sollicitudin malesuada, dictumst ligula--}}
{{--                    luctus urna curae ante. </p>--}}
{{--            </div>--}}
{{--            <div class="wow fadeInUp">--}}
{{--                <div class="row d-flex justify-content-center">--}}
{{--                    <div class="col-md-8 text-center pt-3">--}}
{{--                        <div class="carousel slide carousel-fade" id="carousel-ma" data-ride="carousel">--}}
{{--                            <ol class="carousel-indicators">--}}
{{--                                <li class="active" data-target="#carousel-ma" data-slide-to="0"></li>--}}
{{--                                <li data-target="#carousel-ma" data-slide-to="1"></li>--}}
{{--                                <li data-target="#carousel-ma" data-slide-to="2"></li>--}}
{{--                            </ol>--}}
{{--                            <div class="carousel-inner pb-5" role="listbox">--}}
{{--                                <div class="carousel-item active">--}}
{{--                                    <div class="col"><img class="rounded-circle" src="home/img/client-1.jpg" alt="image" style="width:128px; height:128px;"/>--}}
{{--                                        <p class="pt-4 px-5">"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit, error amet numquam iure provident voluptate esse--}}
{{--                                            quasi" </p>--}}
{{--                                        <p><strong> Robert Smith, MD</strong></p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="carousel-item">--}}
{{--                                    <div class="col"><img class="rounded-circle" src="home/img/client-2.jpg" alt="image" style="width:128px; height:128px;"/>--}}
{{--                                        <p class="pt-4 px-5">"Eros fames mauris condimentum quisque felis ornare phasellus integer curabitur libero vivamus hac penatibus, neque--}}
{{--                                            primis" </p>--}}
{{--                                        <p><strong> Melissa Nelson, CEO</strong></p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="carousel-item">--}}
{{--                                    <div class="col"><img class="rounded-circle" src="home/img/client-3.jpg" alt="image" style="width:128px; height:128px;"/>--}}
{{--                                        <p class="pt-4 px-5">"Aliquam urna semper euismod est eu quis elementum ad, scelerisque sodales platea tortor sapien fames tellus--}}
{{--                                            ullamcorper" </p>--}}
{{--                                        <p><strong> Karen Ford, MD</strong></p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <a class="justify-content-start carousel-control-prev" href="#carousel-ma" role="button" data-slide="prev"><span aria-hidden="true"><i--}}
{{--                                        class="fa fa-caret-left fa-2x indigo-text"></i></span><span class="sr-only text-blue">Previous </span></a><a--}}
{{--                                class="justify-content-end carousel-control-next" href="#carousel-ma" role="button" data-slide="next"><span aria-hidden="true"><i--}}
{{--                                        class="fa fa-caret-right fa-2x indigo-text "></i></span><span class="sr-only text-blue">Next</span></a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <section id="contact">--}}
{{--        <div class="grey lighten-4 py-5">--}}
{{--            <div class="container">--}}
{{--                <div class="wow fadeIn">--}}
{{--                    <h2 class="h1 h1-responsive text-center my-5">Contact us</h2>--}}
{{--                    <p class="px-5 mb-5 pb-3 lead text-center blue-grey-text">--}}
{{--                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit, error amet numquam iure provident voluptate--}}
{{--                        esse quasi, veritatis totam voluptas nostrum quisquam eum porro a pariatur accusamus veniam.--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-5 p-5">--}}
{{--                        <ul class="list-unstyled text-center">--}}
{{--                            <li class="mt-4"><i class="fa fa-map-marker text-default fa-2x"></i>--}}
{{--                                <p class="mt-2"><strong>140, City Center, New York, U.S.A</strong></p>--}}
{{--                            </li>--}}
{{--                            <li class="mt-4"><i class="fa fa-phone text-default fa-2x"></i>--}}
{{--                                <p class="mt-2"><strong>+ 01 234 567 89</strong></p>--}}
{{--                            </li>--}}
{{--                            <li class="mt-4"><i class="fa fa-envelope text-default fa-2x"></i>--}}
{{--                                <p class="mt-2"><strong>contact@company.com</strong></p>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--</div>--}}
<footer class="page-footer special-color-dark">
    <div class="container py-4">
        <div class="container-fluid text-center">
            <p class="mb-0">&copy; <a href="/">{{config('app.name')}}</a>
                {{ config('app.copyright') }}
            </p>
        </div>
    </div>
</footer>
<script type="text/javascript" src="/home/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/home/js/popper.min.js"></script>
<script type="text/javascript" src="/home/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/home/js/mdb.min.js"></script>
<script>new WOW().init();</script>
</body>
</html>
