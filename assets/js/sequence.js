var request = new ajaxRequest();
var sequence = new Vue({
	el: "#sequence_app",
	data: {
		smtpid: "php", clicksubject: "", clickbody: "", clickattachment: "", unsubsmsg: "", sequencedays: "", listid: "", updateidd: "", err: "",
		checkexist: "", seqtitle: "", sequence_id: ''
	},
	mounted: function () {
		document.onreadystatechange = () => {
			if (document.readyState == "complete") {
				this.editform();
			}
		}
	},
	methods: {
		t: function (txt, arr = []) {
			return t(txt, arr);
		},
		editform: function () {
			this.sequence_id = document.getElementById("sequence_id").value;
			if (document.getElementById('seqid') && document.getElementById('seqid').value) {
				this.sequencedays = document.getElementById('sequencee').value;
				this.seqtitle = document.getElementById("seqtitle").value;

				this.smtpid = document.getElementById('smtpid').value;
				this.smtpid = document.getElementById('smtpid').value;
				this.clickbody = document.getElementById('emailbody').value;
				tinymce.get('sequence_editor').setContent(this.clickbody);
				this.clicksubject = document.getElementById('emailsub').value;
				this.unsubsmsg = document.getElementById('unsubs').value;
				this.clickattachment = document.getElementById('unattachment').value;
				var selectlist = document.getElementById('listidd').value;
				var autorespondersarr = selectlist.split("@");
				var selectedautoresponders = document.getElementById("alllistrecords").querySelectorAll("input[type=checkbox]");

				for (var i = 0; i < selectedautoresponders.length; i++) {
					for (var j = 0; j <= autorespondersarr.length; j++) {
						if (autorespondersarr[j] == selectedautoresponders[i].value) {
							selectedautoresponders[i].checked = true;
						}


					}
				}
			}
		},
		sequencesubmit: function () {

			this.checkexist = document.getElementById("checkexist").value;

			this.clickbody = tinyMCE.editors[$('#sequence_editor').attr('id')].getContent();

			var selectedlistids = document.getElementById("alllistrecords").querySelectorAll("input[type=checkbox]");
			const fileInput = $("#attachdata").val();
			
		
			const filename = fileInput.split("/").pop();
			$('.attachdata').text(filename);
			this.listid = "@";
			for (var i = 0; i < selectedlistids.length; i++) {
				if (selectedlistids[i].checked) {
					this.listid += selectedlistids[i].value + '@';
				}
			}
			const allowedExtensions = ["doc", "docx", "pdf", "ppt", "pptx", "xls", "png", "jpeg", "gif"];
			const fileExtension = filename.split(".").pop().toLowerCase();
			if (allowedExtensions.includes(fileExtension) ||  filename == '') {			

				if (document.getElementById('seqid') && document.getElementById('seqid').value) {
					this.updateidd = document.getElementById("seqid").value;
				}
				else {
					this.updateidd = "null";
				}
				if (this.clicksubject.length > 1 && this.seqtitle.length > 1 && this.clickbody.length > 1 && (/^[0-9]+$/.test(this.sequencedays)) && (!isNaN(this.smtpid) || (this.smtpid == "php")) && this.listid.length > 1) {
					var data = { "sequence": 1, "title": this.seqtitle, "smtpid": this.smtpid, "mailsubject": this.clicksubject, "mailbody": this.clickbody, "formData": fileInput, "unsubsmsg": this.unsubsmsg, "sequencedays": this.sequencedays, "listid": this.listid, "updaterec": this.updateidd, "checkexist": this.checkexist, 'sequence_id': this.sequence_id };
					var thisvue = this;
					request.postRequestCb('req.php', data, function (result) {
						if (result.trim() == '1') {
							thisvue.err = "<p><center style='margin: -17px 0px -8px 0px;color: rgb(72, 161, 79);'>" + thisvue.t("Data Saved Successfully.") + "</center></p>";
							document.getElementById('checkexist').value = result.trim();
						}
						else {
							thisvue.err = "<p><center style='margin: -17px 0px -8px 0px;color: red;'>" + thisvue.t("This Setup Already Exists.") + "</center></p>";
						}
					});
				}
				else {
					this.err = "<p><center style='margin: -17px 0px -8px 0px;color: red;'>" + this.t("Please Fill All Fields.") + "</center></p>";
				}
			} else {
				this.err = "<p><center style='margin: -17px 0px -8px 0px;color: red;'>" + this.t("Invalid file extension. Allowed extensions are: " + allowedExtensions.join(", ")) + "</center></p>";
			}
		}
	},
});