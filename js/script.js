/*User signup validation processs*/
$(function(){
  $('#frmSignup').validate({
    rules: {
      inputEmail: {
        required: true,
        email: true
      },
      inputUsername: {
        required: true,
        minlength: 4
      },
      inputPassword: {
        required: true,
          minlength: 8
      },
      inputPasswordConfirm: {
        required: true,
          minlength: 8,
        equalTo: "#myPassword"
      }
    },
    //Sepcifing the validation messages
    messages: {
      inputEmail: {
        required: "<span class='errorMessage'>Please enter an email address!</span>",
        email: "<span class='errorMessage'>Please enter a valid email address!</span>"
      },
      inputUsername: {
        required: "<span class='errorMessage'>Please enter a username!</span>",
        minlength: "<span class='errorMessage'>Username must not be less than 4 characters!</span>",

      },
      inputPassword: {
        required: "<span class='errorMessage'>Please enter a valid password!</span>",
        minlength: "<span class='errorMessage'>Your password must be at least 8 characters long!</span>"
      },
      inputPasswordConfirm: {
        required: "<span class='errorMessage'>Please enter a valid password!</span>",
        minlength: "<span class='errorMessage'>Your password must be at least 8 characters long!</span>",
        equalTo: "<span class='errorMessage'>password did not match!</span>"
      }
    }
  });
});

/*Login validation snippet*/
  $(function(){
    $('#frmLogin').validate({

      rules: {
          inputUsername: {
          required: true
        },
        inputPassword: {
          required: true
        },
        userFeedback: {
          required: true
        }
      },
      //Sepcifing the validation messages
      messages: {
          inputUsername: {
            required: "<span class='errorMessage'>Please enter a username!</span>",

          },
          inputPassword: {
            required: "<span class='errorMessage'>Please enter a valid password</span>"
          },
          userFeedback: {
            required: "<span class='errorMessage'>Please enter your message body</span>"
          }
      }
    });
  });

  /*Email confirmation validation validation snippet*/
    $(function(){
      $('#frmPasswordReset').validate({

        rules: {
            inputUsername: {
            required: true
          }
        },
        //Sepcifing the validation messages
        messages: {
            inputUsername: {
              required: "<span class='errorMessage'>Please enter a username!</span>"
            }
        }
      });
    });

    /*Email confirmation code verification snippet*/
      $(function(){
        $('#frmConfirmAccount').validate({

          rules: {
              inputSecretCode: {
              required: true
            }
          },
          //Sepcifing the validation messages
          messages: {
              inputSecretCode: {
                required: "<span class='errorMessage'>Please enter verification code!</span>"
              }
          }
        });
      });

/*Seeker personal info validation snippet*/
  $(function(){
    $('#frmPersonalInfo').validate({

      rules: {
          inputFirstName: {
          required: true,
          nowhitespace:true,
          lettersonly:true
        },
        inputLastName: {
          required: true
        },
          inputMaritalStatus: {
              required: true
          },
          inputDOB: {
              required: true
          },
          inputTelephone: {
              required: true,
              digits: true,
              minlength: 9
          },
          inputStreet: {
              required: true
          },
          inputProfilePicture: {
              required: true
          },
          city: {
              required: true
          },
          inputProvince: {
              required: true
          },
          inputCountry: {
              required: true
          }
      },
      //Sepcifing the validation messages
      messages: {
          inputFirstName: {
            required: "<span class='errorMessage'>Please enter a First Name!</span>",
            nowhitespace:"<span class='errorMessage'>Only First Name is allowed here!</span>",
            lettersonly:"<span class='errorMessage'>Only letters are allowed</span>"

          },
          inputLastName: {
            required: "<span class='errorMessage'>Please enter Last Name!</span>"
          },
          inputMaritalStatus: {
              required: "<span class='errorMessage'>Please select an option!</span>"
          },
          inputDOB: {
              required: "<span class='errorMessage'>Please enter Date of Birth!</span>"
          },
          inputTelephone: {
              required: "<span class='errorMessage'>Please enter your telephone number!</span>",
              digits: "<span class='errorMessage'>Please enter digits only!</span>",
              minlength: "<span class='errorMessage'>Please enter at least 9 digits!</span>"
          },
          inputStreet: {
              required: "<span class='errorMessage'>Please enter street name!</span>"
          },
          inputProfilePicture: {
              required: "<span class='errorMessage'>Please select a valid photo for upload</span>"
          },
          city: {
              required: "<span class='errorMessage'>Please select your city of birth!</span>"
          },
          inputProvince: {
              required: "<span class='errorMessage'>Please select your province of birth!</span>"
          },
          inputCountry: {
              required: "<span class='errorMessage'>Please select your province of birth!</span>"
          }
      }
    });
  });

/* Seeker Education form validation snippet*/
$(function(){
    $('#frmSeekerEducation').validate({

        rules: {
            inputInstitutionName: {
                required: true
            },
            inputQualification: {
                required: true
            },
            inputCareerField: {
                required: true
            },
            inputStartDate: {
                required: true
            }
        },
        //Sepcifing the validation messages
        messages: {
            inputInstitutionName: {
                required: "<span class='errorMessage'>Please enter name of Institution attended</span>"
            },
            inputQualification: {
                required: "<span class='errorMessage'>Please select qualification attained</span>"
            },
            inputCareerField: {
                required: "<span class='errorMessage'>Please select Field of Study</span>"
            },
            inputStartDate: {
                required: "<span class='errorMessage'>Please select start date</span>"
            }
        }
    });
});


/*Password Reset validation snippet*/
$(function(){
    $('#frmPasswordResetFinal').validate({

        rules: {
            inputPassword: {
                required: true,
                minlength: 8
            },
            inputPasswordConfirm: {
                required: true,
                minlength: 8,
                equalTo: "#myPassword"
            }
        },
        //Sepcifing the validation messages
        messages: {
            inputPassword: {
                required: "<span class='errorMessage'>Please enter a valid password!</span>",
                minlength: "<span class='errorMessage'>Your password must be at least 8 characters long!</span>"
            },
            inputPasswordConfirm: {
                required: "<span class='errorMessage'>Please enter a valid password!</span>",
                minlength: "<span class='errorMessage'>Your password must be at least 8 characters long!</span>",
                equalTo: "<span class='errorMessage'>password did not match!</span>"
            }
        }
    });
});




/*Seeker Experience validation snippet*/
  $(function(){
    $('#frmSeekerExperience').validate({

      rules: {
          inputJobTitle: {
          required: true
        },
        inputOrganization: {
        required: true
        },
        inputOccupationalField: {
          required: true
        },
        inputCareerField: {
          required: true
        },
        inputStartDate: {
          required: true
        }
      },
      //Sepcifing the validation messages
      messages: {
          inputJobTitle: {
            required: "<span class='errorMessage'>Please enter Job Title</span>"
          },
          inputOrganization: {
            required: "<span class='errorMessage'>Please enter the Organization name</span>"
          },
          inputOccupationalField: {
            required: "<span class='errorMessage'>Please enter a valid occupational field</span>"
          },
          inputCareerField: {
            required: "<span class='errorMessage'>Please select field of study</span>"
          },
          inputStartDate: {
            required: "<span class='errorMessage'>Please enter start date</span>"
          }
      }
    });
  });

  /*Seeker skills validation snippet*/
    $(function(){
      $('#frmSeekerSkills').validate({

        rules: {
            inputLanguage: {
            required: true
          },
          inputComputerSkill: {
            required: true
          },
          inputLeadershipSkill: {
            required: true
          },
          inputInterest: {
            required: true
          }
        },
        //Sepcifing the validation messages
        messages: {
            inputLanguage: {
              required: "<span class='errorMessage'>Please enter languages known</span>",

            },
            inputComputerSkill: {
              required: "<span class='errorMessage'>Please enlist your Computer Skills</span>"
            },
            inputLeadershipSkill: {
              required: "<span class='errorMessage'>Please comment on your leadership skills</span>"
            },
            inputInterest: {
              required: "<span class='errorMessage'>Please enter your special skills</span>"
            }
        }
      });
    });


    /*Seeker references validation snippet*/
      $(function(){
        $('#frmSeekerReference').validate({

          rules: {
              inputFirstName: {
              required: true
            },
            inputLastName: {
              required: true
            },
            inputOrganization: {
              required: true
            },
            inputEmail: {
              required: true
            },
            inputTelephone: {
              required: true,
              digits:true,
              minlength:9
            }
          },
          //Sepcifing the validation messages
          messages: {
              inputFirstName: {
                required: "<span class='errorMessage'>Please enter First Name</span>",

              },
              inputLastName: {
                required: "<span class='errorMessage'>Please enter Last Name</span>"
              },
              inputOrganization: {
                required: "<span class='errorMessage'>Enter Reference\'s Organization</span>"
              },
              inputEmail: {
                required: "<span class='errorMessage'>Please enter an email address</span>"
              },
              inputTelephone: {
                required: "<span class='errorMessage'>Please enter a mobile number</span>",
                digits: "<span class='errorMessage'>Please enter digits only!</span>",
                minlength: "<span class='errorMessage'>Please enter at least 9 digits!</span>"
              }
          }
        });
      });


      /*Seeker personal info validation snippet*/
        $(function(){
          $('#frmRecruiterPersnalInfo').validate({

            rules: {
                inputFirstName: {
                required: true
              },
              inputLastName: {
                required: true
              },
                inputMaritalStatus: {
                    required: true
                },
                inputDOB: {
                    required: true
                },
                inputTelephone: {
                    required: true,
                    digits: true,
                    minlength: 9
                },
                inputStreet: {
                    required: true
                },
                inputProfilePicture: {
                    required: true
                },
                city: {
                    required: true
                },
                inputProvince: {
                    required: true
                },
                inputCountry: {
                    required: true
                },
                inputOrganization: {
                    required: true
                },
                inputWebLink: {
                    required: true,
                    url:true
                }
            },
            //Sepcifing the validation messages
            messages: {
                inputFirstName: {
                  required: "<span class='errorMessage'>Please enter a First Name!</span>",

                },
                inputLastName: {
                  required: "<span class='errorMessage'>Please enter Last Name!</span>"
                },
                inputMaritalStatus: {
                    required: "<span class='errorMessage'>Please select an option!</span>"
                },
                inputDOB: {
                    required: "<span class='errorMessage'>Please enter Date of Birth!</span>"
                },
                inputTelephone: {
                    required: "<span class='errorMessage'>Please enter your telephone number!</span>",
                    digits: "<span class='errorMessage'>Please enter digits only!</span>",
                    minlength: "<span class='errorMessage'>Please enter at least 9 digits!</span>"
                },
                inputStreet: {
                    required: "<span class='errorMessage'>Please enter street name!</span>"
                },
                inputProfilePicture: {
                    required: "<span class='errorMessage'>Please select a valid photo for upload</span>"
                },
                city: {
                    required: "<span class='errorMessage'>Please select your city of birth!</span>"
                },
                inputProvince: {
                    required: "<span class='errorMessage'>Please select your province of birth!</span>"
                },
                inputCountry: {
                    required: "<span class='errorMessage'>Please select your province of birth!</span>"
                },
                inputOrganization: {
                    required: "<span class='errorMessage'>Please enter the name of your Organization</span>"
                },
                inputWebLink: {
                    required: "<span class='errorMessage'>Please enter a url</span>",
                    url: "<span class='errorMessage'>Please enter a valid URL!</span>",
                }
            }
          });
        });

        /*Seeker personal info validation snippet*/
          $(function(){
            $('#frmJobPosting').validate({

              rules: {
                  inputJobTitle: {
                  required: true
                },
                inputOccupationalField: {
                  required: true
                },
                  inputEmloymentType: {
                      required: true
                  },
                  inputWorkExperience: {
                      required: true
                  },
                  inputSalary: {
                      required: true,
                      digits: true
                  },
                  inputLanguage: {
                      required: true
                  },
                  inputSkillAndAbilities: {
                      required: true
                  },
                  inputJobDescription: {
                      required: true
                  },
                  inputURLTitle: {
                      required: true
                  },
                  inputCareerField: {
                      required: true
                  },
                  inputQualification: {
                      required: true
                  },
                  inputJobAttachment: {
                      required: true
                  },
                  city: {
                      required: true
                  },
                  inputProvince: {
                      required: true
                  },
                  inputWebLink: {
                      required: true,
                      url:true
                  }
              },
              //Sepcifing the validation messages
              messages: {
                  inputJobTitle: {
                    required: "<span class='errorMessage'>Please enter job title!</span>",

                  },
                  inputOccupationalField: {
                    required: "<span class='errorMessage'>Please enter the occupational Field</span>"
                  },
                  inputEmloymentType: {
                      required: "<span class='errorMessage'>Please select an option!</span>"
                  },
                  inputWorkExperience: {
                      required: "<span class='errorMessage'>Please select an option!</span>"
                  },
                  inputSalary: {
                      required: "<span class='errorMessage'>Please enter the salary scale</span>",
                      digits: "<span class='errorMessage'>Please enter only numbers</span>"
                  },
                  inputLanguage: {
                      required: "<span class='errorMessage'>Please enter the languages required</span>"
                  },
                  inputSkillAndAbilities: {
                      required: "<span class='errorMessage'>Please enlist our skills and abilities</span>"
                  },
                  inputJobDescription: {
                      required: "<span class='errorMessage'>Please enter job description</span>"
                  },
                  inputURLTitle: {
                      required: "<span class='errorMessage'>Please enter Link title</span>"
                  },
                  inputCareerField: {
                      required: "<span class='errorMessage'>Please select an option</span>"
                  },
                  inputQualification: {
                      required: "<span class='errorMessage'>Please select an option</span>"
                  },
                  inputJobAttachment: {
                      required: "<span class='errorMessage'>Please select a valid document for upload</span>"
                  },
                  city: {
                      required: "<span class='errorMessage'>Please select an option</span>"
                  },
                  inputProvince: {
                      required: "<span class='errorMessage'>Please select an option</span>"
                  },
                  inputWebLink: {
                      required: "<span class='errorMessage'>Please enter a url</span>",
                      url: "<span class='errorMessage'>Please enter a valid URL!</span>",
                  }
              }
            });
          });
          $(function ()
                 { $("[data-toggle='popover']"). popover();
                 });
