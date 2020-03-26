<div class="col-lg-4 col-md-12 col-sm-12 footer-ct-content">
	<div class="row">
        @if ($velocityMetaData)
            {!! $velocityMetaData->footer_middle_content !!}
        @else
            <div class="col-lg-6 col-md-12 col-sm-12 no-padding">
                <ul type="none">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Customer Service</a></li>
                    <li><a href="#">What&rsquo;s New</a></li>
                    <li><a href="#">Contact Us </a></li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 no-padding">
                <ul type="none">
                    <li><a href="#"> Order and Returns </a></li>
                    <li><a href="#"> Payment Policy </a></li>
                    <li><a href="#"> Shipping Policy</a></li>
                    <li><a href="#"> Privacy and Cookies Policy </a></li>
                </ul>
            </div>
        @endif
	</div>
</div>
