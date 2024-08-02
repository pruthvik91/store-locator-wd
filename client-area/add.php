<?php 
require_once('../config.php');
require_once('uservalidation.php');
require_once('header.php'); ?>

<nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Forms</a></li>
						<li class="breadcrumb-item active" aria-current="page">Advanced Elements</li>
					</ol>
				</nav>

				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title">Form Validation</h4>
								<p class="card-description">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery Validation Documentation </a>for a full list of instructions and other options.</p>
								<form class="cmxform" id="signupForm" method="get" action="#">
									<fieldset>
										<div class="form-group">
											<label for="name">Name</label>
											<input id="name" class="form-control" name="name" type="text">
										</div>
										<div class="form-group">
											<label for="exampleInputText1">Text Input</label>
											<input type="text" class="form-control" id="exampleInputText1" value="" placeholder="Enter Name" required>
										</div>
										<div class="form-group">
											<label for="email">Email</label>
											<input id="email" class="form-control" name="email" type="email">
										</div>
										<div class="form-group">
											<label for="password">Password</label>
											<input id="password" class="form-control" name="password" type="password">
										</div>
										<div class="form-group">
											<label for="confirm_password">Confirm password</label>
											<input id="confirm_password" class="form-control" name="confirm_password" type="password">
										</div>
										<div class="form-group">
											<label for="exampleInputText1">Text Input</label>
											<input type="text" class="form-control" id="exampleInputText1" value="" placeholder="Enter Name" required>
										</div>
										<div class="form-group">
											<label for="exampleInputEmail3">Email Input</label>
											<input type="email" class="form-control" id="exampleInputEmail3" value="" placeholder="Enter Email" required>
										</div>
										<div class="form-group">
											<label for="exampleInputNumber1">Number Input</label>
											<input type="number" class="form-control" id="exampleInputNumber1" value="" required>
										</div>
										<div class="form-group">
											<label for="exampleInputPassword3">Password Input</label>
											<input type="password" class="form-control" id="exampleInputPassword3" value="" placeholder="Enter Password" required>
										</div>
										<div class="form-group">
											<label for="exampleInputDisabled1">Disabled Input</label>
											<input type="text" class="form-control" id="exampleInputDisabled1" disabled value="Amiah Burton" required>
										</div>
										<div class="form-group">
											<label for="exampleInputPlaceholder">Placeholder</label>
											<input type="text" class="form-control" id="exampleInputPlaceholder" placeholder="Enter Your Name" required>
										</div>
										<div class="form-group">
											<label for="exampleInputReadonly">Readonly</label>
											<input type="text" class="form-control" id="exampleInputReadonly" readonly value="Amiah Burton" required>
										</div>
										<div class="form-group">
											<label for="exampleFormControlSelect1">Select Input</label>
											<select class="form-control" id="exampleFormControlSelect1" required>
												<option selected disabled>Select your age</option>
												<option>12-18</option>
												<option>18-22</option>
												<option>22-30</option>
												<option>30-60</option>
												<option>Above 60</option>
											</select>
										</div>
										<div class="form-group">
											<label for="exampleFormControlSelect2">Example multiple select</label>
											<select multiple class="form-control" id="exampleFormControlSelect2" required>
												<option>1</option>
												<option>2</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
												<option>6</option>
												<option>7</option>
												<option>8</option>
											</select>
										</div>
										<div class="form-group">
											<label for="exampleFormControlTextarea1">Example textarea</label>
											<textarea class="form-control" id="exampleFormControlTextarea1" rows="5" required></textarea>
										</div>
										<div class="form-groupp">
											<label for="customRange1">Range Input</label>
											<input type="range" class="custom-range" id="customRange1" required>
										</div>
										<div class="form-group">
											<div class="form-check">
												<label class="form-check-label">
													<input type="checkbox" class="form-check-input" required>
													Default checkbox
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input type="checkbox" checked class="form-check-input" required>
													Checked
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input type="checkbox" disabled class="form-check-input" >
													Disabled checkbox
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input type="checkbox" class="form-check-input" disabled checked >
													Disabled checked
												</label>
											</div>
										</div>
										<div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<input type="checkbox" class="form-check-input" required>
													Inline checkbox
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<input type="checkbox" checked class="form-check-input" required>
													Checked
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<input type="checkbox" disabled class="form-check-input" required>
													Inline disabled checkbox
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<input type="checkbox" class="form-check-input" disabled checked>
													Disabled checked
												</label>
											</div>
										</div>
										<div class="form-group">
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios" value="option1">
													Default
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1">
													Default
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios2" id="optionsRadios2" value="option2" checked="">
													Selected
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios3" id="optionsRadios3" value="option3" disabled="">
													Disabled
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios4" id="optionsRadios4" value="option4" disabled="" checked="">
													Selected and disabled
												</label>
											</div>
										</div>
										<div class="form-group">
											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios5" id="optionsRadios5" value="option5">
													Default
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios5" id="optionsRadios6" value="option5">
													Default
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios7" id="optionsRadios7" value="option6" checked="">
													Selected
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios8" id="optionsRadios8" value="option7" disabled="">
													Disabled
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<input type="radio" class="form-check-input" name="optionsRadios9" id="optionsRadios9" value="option8" disabled="" checked="">
													Selected and disabled
												</label>
											</div>
										</div>
										<div class="form-group">
											<label>File upload</label>
											<input type="file" name="img[]" class="file-upload-default">
											<div class="input-group col-xs-12">
												<input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
												<span class="input-group-append">
													<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
												</span>
											</div>
										</div>
										<div class="form-group">
											<label>Single select box using select 2</label>
											<select class="js-example-basic-single w-100">
												<option value="TX">Texas</option>
												<option value="NY">New York</option>
												<option value="FL">Florida</option>
												<option value="KN">Kansas</option>
												<option value="HW">Hawaii</option>
											</select>
										</div>
										<div class="form-group row">
											<div class="col">
												<label>Date:</label>
												<input class="form-control mb-4 mb-md-0" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy"/>
											</div>
											<div class="col-md-6">
												<label>Time (12 hour):</label>
												<input class="form-control" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="hh:mm tt" />
											</div>
										</div>
										<div class="form-group">										
											<label>Date time:</label>
											<input class="form-control mb-4 mb-md-0" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy HH:MM:ss" />											
										</div>
										<label>Color:</label>
										<div id="cp1" class="input-group mb-4" title="Using input value">											
											<input type="text" class="form-control input-lg" value="#DD0F20FF"/>
											<span class="input-group-append">
												<span class="input-group-text colorpicker-input-addon"><i></i></span>
											</span>
										</div>
										<input class="btn btn-primary" type="submit" value="Submit">
									</fieldset>
								</form>
							</div>
						</div>
					</div>
					
                </div>	
            </div>	

<?php require_once('footer.php'); ?>