ALTER TABLE 'mission'
MODIFY COLUMN 'missionId' int(10) UNSIGNED NOT NULL FIRST;
ALTER TABLE 'missionRecord'
MODIFY COLUMN 'missionId' int(10) UNSIGNED NOT NULL FIRST;
ALTER TABLE 'mission'
MODIFY COLUMN 'endImage' varchar(255) NULL DEFAULT NULL AFTER 'preMissionId';
ALTER TABLE 'achievement'
MODIFY COLUMN 'achievementId'  int(10) UNSIGNED NOT NULL FIRST ;

UPDATE mission SET endImage='item,31' WHERE missionId=2;
UPDATE mission SET endImage='seed,1000' WHERE missionId=3;
UPDATE mission SET endImage='item,1' WHERE missionId=1001;
UPDATE mission SET endImage='seed,3007' WHERE missionId=1004;
UPDATE mission SET endImage='seed,1007' WHERE missionId=1005;
UPDATE mission SET endImage='seed,3006' WHERE missionId=1006;
UPDATE mission SET endImage='seed,1008' WHERE missionId=1011;
UPDATE mission SET endImage='seed,3008' WHERE missionId=1012;
UPDATE mission SET endImage='seed,3009' WHERE missionId=1013;
UPDATE mission SET endImage='item,2' WHERE missionId=1014;
UPDATE mission SET endImage='item,25' WHERE missionId=1016;
UPDATE mission SET endImage='seed,1009' WHERE missionId=1017;
UPDATE mission SET endImage='seed,1010' WHERE missionId=1018;
UPDATE mission SET endImage='seed,3010' WHERE missionId=1019;
UPDATE mission SET endImage='item,2' WHERE missionId=1020;
UPDATE mission SET endImage='seed,1011' WHERE missionId=1024;
UPDATE mission SET endImage='seed,3011' WHERE missionId=1025;
UPDATE mission SET endImage='seed,3012' WHERE missionId=1026;
UPDATE mission SET endImage='item,12' WHERE missionId=1027;
UPDATE mission SET endImage='item,7' WHERE missionId=1029;
UPDATE mission SET endImage='seed,1012' WHERE missionId=1034;
UPDATE mission SET endImage='seed,1013' WHERE missionId=1035;
UPDATE mission SET endImage='seed,3013' WHERE missionId=1036;
UPDATE mission SET endImage='seed,3014' WHERE missionId=1037;
UPDATE mission SET endImage='item,18' WHERE missionId=1040;
UPDATE mission SET endImage='seed,1014' WHERE missionId=1041;
UPDATE mission SET endImage='seed,3015' WHERE missionId=1042;
UPDATE mission SET endImage='seed,1015' WHERE missionId=1043;
UPDATE mission SET endImage='seed,1016' WHERE missionId=1048;
UPDATE mission SET endImage='seed,3016' WHERE missionId=1049;
UPDATE mission SET endImage='seed,3017' WHERE missionId=1050;
UPDATE mission SET endImage='seed,1017' WHERE missionId=1051;
UPDATE mission SET endImage='seed,1018' WHERE missionId=1052;
UPDATE mission SET endImage='seed,3018' WHERE missionId=1053;
UPDATE mission SET endImage='seed,3019' WHERE missionId=1054;
UPDATE mission SET endImage='item,24' WHERE missionId=1055;
UPDATE mission SET endImage='item,29' WHERE missionId=1056;
UPDATE mission SET endImage='item,4' WHERE missionId=1057;
UPDATE mission SET endImage='item,1' WHERE missionId=1058;
UPDATE mission SET endImage='item,1' WHERE missionId=1059;
UPDATE mission SET endImage='item,1' WHERE missionId=1060;
UPDATE mission SET endImage='seed,1021' WHERE missionId=1065;
UPDATE mission SET endImage='seed,3021' WHERE missionId=1066;
UPDATE mission SET endImage='seed,3022' WHERE missionId=1067;
UPDATE mission SET endImage='seed,1022' WHERE missionId=1070;
UPDATE mission SET endImage='seed,1023' WHERE missionId=1071;
UPDATE mission SET endImage='seed,1024' WHERE missionId=1072;
UPDATE mission SET endImage='seed,3023' WHERE missionId=1073;
UPDATE mission SET endImage='seed,1025' WHERE missionId=1077;
UPDATE mission SET endImage='seed,3024' WHERE missionId=1078;
UPDATE mission SET endImage='seed,3025' WHERE missionId=1079;
UPDATE mission SET endImage='seed,1026' WHERE missionId=1080;
UPDATE mission SET endImage='seed,3026' WHERE missionId=1081;
UPDATE mission SET endImage='seed,1027' WHERE missionId=1082;
UPDATE mission SET endImage='seed,3027' WHERE missionId=1083;
UPDATE mission SET endImage='seed,1028' WHERE missionId=1084;
UPDATE mission SET endImage='seed,1029' WHERE missionId=1085;
UPDATE mission SET endImage='seed,3028' WHERE missionId=1086;
UPDATE mission SET endImage='seed,1030' WHERE missionId=1087;
UPDATE mission SET endImage='seed,3029' WHERE missionId=1088;
UPDATE mission SET endImage='seed,3030' WHERE missionId=1089;