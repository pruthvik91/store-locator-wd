<?php 
require_once('../config.php');
require_once('uservalidation.php');
require_once('header.php'); ?>

<nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Tables</a></li>
						<li class="breadcrumb-item active" aria-current="page">Basic Tables</li>
					</ol>
				</nav>

				<div class="row">
					<div class="col-md-12 stretch-card grid-margin">
						<div class="card">
							<div class="card-body">
								<h6 class="card-title">Form Grid</h6>
								<form>										
									<div class="row">
										<div class="col-sm-4">																			
								<div class="form-group">
									<label>City</label>
									<select class="js-example-basic-single w-100 form-control-md">
										<option value="TX">Texas</option>
										<option value="NY">New York</option>
										<option value="FL">Florida</option>
										<option value="KN">Kansas</option>
										<option value="HW">Hawaii</option>
									</select>
								</div>
										</div><!-- Col -->
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label">State</label>
												<input type="text" class="form-control" placeholder="Enter state">
											</div>
										</div><!-- Col -->
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label">Zip</label>
												<input type="text" class="form-control" placeholder="Enter zip code">
											</div>
										</div><!-- Col -->
									</div><!-- Row -->										
								</form>
								<button type="button" class="btn btn-primary btn-icon-text submit">
									<i class="btn-icon-prepend" data-feather="search"></i>
									Search
								</button>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="row">					
					<div class="col-lg-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<h6 class="card-title">Hoverable Table
									<a href="#" class="btn btn-primary btn-icon-text float-right">									
										<i class="btn-icon-prepend" data-feather="plus"></i>
										Add New
									</a>
								</h6>
								
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th>#</th>
												<th>First Name</th>
												<th>LAST NAME</th>
												<th>USERNAME</th>
												<th>Action</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>1</th>
												<td>Mark</td>
												<td>Otto</td>
												<td>@mdo</td>
												<td>
													<a href="#" class="btn btn-primary btn-icon-text">									
														<i class="btn-icon-prepend" data-feather="edit"></i>
														Edit
													</a>
												</td>
												<td>
													<div class="dropdown mb-2">
                        <button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2">
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                        </div>
                      </div>
												</td>
											</tr>
											<tr>
												<th>2</th>
												<td>Jacob</td>
												<td>Thornton</td>
												<td>@fat</td>
												<td>
													<a href="#" class="btn btn-primary btn-icon-text">									
														<i class="btn-icon-prepend" data-feather="edit"></i>
														Edit
													</a>													
												</td>
												<td>
													<div class="dropdown mb-2">
                        <button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2">
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                        </div>
                      </div>
												</td>
											</tr>
											<tr>
												<th>3</th>
												<td>Larry</td>
												<td>the Bird</td>
												<td>@twitter</td>
												<td>
													<a href="#" class="btn btn-primary btn-icon-text">									
														<i class="btn-icon-prepend" data-feather="edit"></i>
														Edit
													</a>
												</td>
												<td>
													<div class="dropdown mb-2">
                        <button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2">
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                        </div>
                      </div>
												</td>
											</tr>
											<tr>
												<th>4</th>
												<td>Larry</td>
												<td>Jellybean</td>
												<td>@lajelly</td>
												<td>
													<a href="#" class="btn btn-primary btn-icon-text">									
														<i class="btn-icon-prepend" data-feather="edit"></i>
														Edit
													</a>
												</td>
												<td>
													<div class="dropdown mb-2">
                        <button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2">
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                        </div>
                      </div>
												</td>
											</tr>
											<tr>
												<th>5</th>
												<td>Larry</td>
												<td>Kikat</td>
												<td>@lakitkat</td>
												<td>
													<a href="#" class="btn btn-primary btn-icon-text">									
														<i class="btn-icon-prepend" data-feather="edit"></i>
														Edit
													</a>														
												</td>
												<td>
													<div class="dropdown mb-2">
                        <button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2">
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                          <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                        </div>
                      </div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<ul class="pagination justify-content-center">
					<li class="page-item"><a class="page-link" href="#">Previous</a></li>
					<li class="page-item"><a class="page-link" href="#">1</a></li>
					<li class="page-item"><a class="page-link" href="#">2</a></li>
					<li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </div>
<?php require_once('footer.php'); ?>