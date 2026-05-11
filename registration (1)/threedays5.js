      // ========== 3-DAY PASS TOGGLE ==========
        function initThreeDayToggle() {
            document.getElementById("threedays").addEventListener("change", function () {
                if (this.checked) {
                    disableDay1();
                    disableDay2();
                    disableDay3();
                } else {
                    enableDay1();
                    enableDay2();
                    enableDay3();
                }
            });
        }

        function disableDay1() {
            // Disable radios
            document.querySelectorAll('input[name="event_day1"]').forEach(el => {
                el.checked = false;
                el.disabled = true;
            });

            // Disable selects
            const fields = [
                ["cmorning1", "lcmorning1"],
                ["cafternoon1", "lcafternoon1"],
                ["fsession1", "lfsession1"]
            ];

            fields.forEach(([field, label]) => {
                document.getElementById(field).value = "";
                document.getElementById(field).style.display = "none";
                document.getElementById(label).style.display = "none";
                document.getElementById(field).disabled = true;
            });
        }

        function enableDay1() {
            document.querySelectorAll('input[name="event_day1"]').forEach(el => {
                el.disabled = false;
            });

            const fields = [
                ["cmorning1", "lcmorning1"],
                ["cafternoon1", "lcafternoon1"],
                ["fsession1", "lfsession1"]
            ];

            fields.forEach(([field, label]) => {
                document.getElementById(field).disabled = false;
                document.getElementById(field).style.display = "none";
                document.getElementById(label).style.display = "none";
                document.getElementById(field).value = "";
            });
        }

        // ========== SHOW/HIDE MORNING/AFTERNOON/FULL ==========
        function toggleSession(day, type) {
            if (document.getElementById("threedays").checked) return;

            let morning = "cmorning" + day;
            let afternoon = "cafternoon" + day;
            let full = "fsession" + day;

            let lm = "lcmorning" + day;
            let la = "lcafternoon" + day;
            let lf = "lfsession" + day;

            if (type === "custom") {
                document.getElementById(morning).style.display = "block";
                document.getElementById(lm).style.display = "block";

                document.getElementById(afternoon).style.display = "block";
                document.getElementById(la).style.display = "block";

                document.getElementById(full).style.display = "none";
                document.getElementById(lf).style.display = "none";
            } else {
                document.getElementById(morning).style.display = "none";
                document.getElementById(lm).style.display = "none";

                document.getElementById(afternoon).style.display = "none";
                document.getElementById(la).style.display = "none";

                document.getElementById(full).style.display = "block";
                document.getElementById(lf).style.display = "block";
            }
        }


//enableDay2

        function disableDay2() {
            // Disable radios
            document.querySelectorAll('input[name="event_day2"]').forEach(el => {
                el.checked = false;
                el.disabled = true;
            });

            // Disable selects
            const fields2 = [
                ["cmorning2", "lcmorning2"],
                ["cafternoon2", "lcafternoon2"],
                ["fsession2", "lfsession2"]
            ];

            fields2.forEach(([field2, label]) => {
                document.getElementById(field2).value = "";
                document.getElementById(field2).style.display = "none";
                document.getElementById(label).style.display = "none";
                document.getElementById(field2).disabled = true;
            });
        }

        function enableDay2() {
            document.querySelectorAll('input[name="event_day2"]').forEach(el => {
                el.disabled = false;
            });

            const fields2 = [
                ["cmorning2", "lcmorning2"],
                ["cafternoon2", "lcafternoon2"],
                ["fsession2", "lfsession2"]
            ];

            fields2.forEach(([field2, label]) => {
                document.getElementById(field2).disabled = false;
                document.getElementById(field2).style.display = "none";
                document.getElementById(label).style.display = "none";
                document.getElementById(field2).value = "";
            });
        }

        // ========== SHOW/HIDE MORNING/AFTERNOON/FULL ==========
        function toggleSession(day, type) {
            if (document.getElementById("threedays").checked) return;

            let morning2 = "cmorning" + day;
            let afternoon2 = "cafternoon" + day;
            let full2 = "fsession" + day;

            let lm2 = "lcmorning" + day;
            let la2 = "lcafternoon" + day;
            let lf2 = "lfsession" + day;

            if (type === "custom") {
                document.getElementById(morning2).style.display = "block";
                document.getElementById(lm2).style.display = "block";

                document.getElementById(afternoon2).style.display = "block";
                document.getElementById(la2).style.display = "block";

                document.getElementById(full2).style.display = "none";
                document.getElementById(lf2).style.display = "none";
            } else {
                document.getElementById(morning2).style.display = "none";
                document.getElementById(lm2).style.display = "none";

                document.getElementById(afternoon2).style.display = "none";
                document.getElementById(la2).style.display = "none";

                document.getElementById(full2).style.display = "block";
                document.getElementById(lf2).style.display = "block";
            }
        }
        
        
//3 days  


        function disableDay3() {
            // Disable radios
            document.querySelectorAll('input[name="event_day3"]').forEach(el => {
                el.checked = false;
                el.disabled = true;
            });

            // Disable selects
            const fields3 = [
                ["cmorning3", "lcmorning3"],
                ["cafternoon3", "lcafternoon3"],
                ["fsession3", "lfsession3"]
            ];

            fields3.forEach(([field3, label]) => {
                document.getElementById(field3).value = "";
                document.getElementById(field3).style.display = "none";
                document.getElementById(label).style.display = "none";
                document.getElementById(field3).disabled = true;
            });
        }

        function enableDay3() {
            document.querySelectorAll('input[name="event_day3"]').forEach(el => {
                el.disabled = false;
            });

            const fields3 = [
                ["cmorning3", "lcmorning3"],
                ["cafternoon3", "lcafternoon3"],
                ["fsession3", "lfsession3"]
            ];

            fields3.forEach(([field3, label]) => {
                document.getElementById(field3).disabled = false;
                document.getElementById(field3).style.display = "none";
                document.getElementById(label).style.display = "none";
                document.getElementById(field3).value = "";
            });
        }

        // ========== SHOW/HIDE MORNING/AFTERNOON/FULL ==========
        function toggleSession(day, type) {
            if (document.getElementById("threedays").checked) return;

            let morning3 = "cmorning" + day;
            let afternoon3 = "cafternoon" + day;
            let full3 = "fsession" + day;

            let lm3 = "lcmorning" + day;
            let la3 = "lcafternoon" + day;
            let lf3 = "lfsession" + day;

            if (type === "custom") {
                document.getElementById(morning3).style.display = "block";
                document.getElementById(lm3).style.display = "block";

                document.getElementById(afternoon3).style.display = "block";
                document.getElementById(la3).style.display = "block";

                document.getElementById(full3).style.display = "none";
                document.getElementById(lf3).style.display = "none";
            } else {
                document.getElementById(morning3).style.display = "none";
                document.getElementById(lm3).style.display = "none";

                document.getElementById(afternoon3).style.display = "none";
                document.getElementById(la3).style.display = "none";

                document.getElementById(full3).style.display = "block";
                document.getElementById(lf3).style.display = "block";
            }
        }

        window.onload = initThreeDayToggle;
  