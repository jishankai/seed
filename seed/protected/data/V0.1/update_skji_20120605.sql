ALTER TABLE `player`
ADD UNIQUE INDEX `playerName` (`playerName`) USING BTREE;
ALTER TABLE `player`
ADD UNIQUE INDEX `userId` (`userId`) USING BTREE ;
ALTER TABLE `user`
ADD UNIQUE INDEX `deviceId` (`deviceId`) USING BTREE ;