CREATE TABLE [dbo].[AuthToken] (
    [AuthTokenId] [int] NOT NULL,
    [UserId] [int] NOT NULL,
    [Expires] [datetime],
    [Selector] [int] NOT NULL,
    [Token] [nvarchar](max),
    CONSTRAINT [PK_dbo.AuthToken] PRIMARY KEY ([AuthTokenId])
)
CREATE INDEX [IX_AuthTokenId] ON [dbo].[AuthToken]([AuthTokenId])
CREATE TABLE [dbo].[User] (
    [UserId] [int] NOT NULL IDENTITY,
    [Username] [nvarchar](250),
    [Password] [nvarchar](300),
    [Birthdate] [datetime],
    [EMail] [nvarchar](300),
    [Description] [nvarchar](3000),
    [PictureLink] [nvarchar](200),
    [LockoutEnabled] [bit] NOT NULL,
    [LockoutEndDate] [datetime],
    [AccessFailedCount] [int] NOT NULL,
    [RoleId] [int] NOT NULL,
    [AuthTokenId] [int],
    CONSTRAINT [PK_dbo.User] PRIMARY KEY ([UserId])
)
CREATE INDEX [IX_RoleId] ON [dbo].[User]([RoleId])
CREATE TABLE [dbo].[Comment] (
    [CommentId] [int] NOT NULL IDENTITY,
    [Message] [nvarchar](500),
    [Created] [datetime] NOT NULL DEFAULT GETDATE(),
    [UserId] [int] NOT NULL,
    [UserFile_UserFileId] [int],
    CONSTRAINT [PK_dbo.Comment] PRIMARY KEY ([CommentId])
)
CREATE INDEX [IX_UserId] ON [dbo].[Comment]([UserId])
CREATE INDEX [IX_UserFile_UserFileId] ON [dbo].[Comment]([UserFile_UserFileId])
CREATE TABLE [dbo].[Entry] (
    [EntryId] [int] NOT NULL IDENTITY,
    [Message] [nvarchar](max),
    [Created] [datetime] NOT NULL DEFAULT GETDATE(),
    [IsDeleted] [bit] NOT NULL,
    [ForumThreadId] [int] NOT NULL,
    [UserId] [int] NOT NULL,
    CONSTRAINT [PK_dbo.Entry] PRIMARY KEY ([EntryId])
)
CREATE INDEX [IX_ForumThreadId] ON [dbo].[Entry]([ForumThreadId])
CREATE INDEX [IX_UserId] ON [dbo].[Entry]([UserId])
CREATE TABLE [dbo].[ForumThread] (
    [ForumThreadId] [int] NOT NULL IDENTITY,
    [Title] [nvarchar](500),
    [Description] [nvarchar](2000),
    [IsDeleted] [bit] NOT NULL,
    [Created] [datetime] NOT NULL DEFAULT GETDATE(),
    [UserId] [int] NOT NULL,
    CONSTRAINT [PK_dbo.ForumThread] PRIMARY KEY ([ForumThreadId])
)
CREATE INDEX [IX_UserId] ON [dbo].[ForumThread]([UserId])
CREATE TABLE [dbo].[UserFile] (
    [UserFileId] [int] NOT NULL IDENTITY,
    [Name] [nvarchar](200),
    [FileLink] [nvarchar](250),
    [Description] [nvarchar](3000),
    [IsPrivate] [bit] NOT NULL,
    [UserId] [int] NOT NULL,
    [Uploaded] [datetime] NOT NULL DEFAULT GETDATE(),
    CONSTRAINT [PK_dbo.UserFile] PRIMARY KEY ([UserFileId])
)
CREATE INDEX [IX_UserId] ON [dbo].[UserFile]([UserId])
CREATE TABLE [dbo].[Role] (
    [RoleId] [int] NOT NULL IDENTITY,
    [Name] [nvarchar](max),
    [FileDownload] [bit] NOT NULL,
    [ReadForum] [bit] NOT NULL,
    [ReadComments] [bit] NOT NULL,
    [FileUpload] [bit] NOT NULL,
    [WriteForum] [bit] NOT NULL,
    [WriteComments] [bit] NOT NULL,
    CONSTRAINT [PK_dbo.Role] PRIMARY KEY ([RoleId])
)
CREATE TABLE [dbo].[LogEntry] (
    [LogEntryId] [int] NOT NULL IDENTITY,
    [Created] [datetime] NOT NULL DEFAULT GETDATE(),
    [Message] [nvarchar](max),
    [Typ] [nvarchar](max),
    CONSTRAINT [PK_dbo.LogEntry] PRIMARY KEY ([LogEntryId])
)
ALTER TABLE [dbo].[AuthToken] ADD CONSTRAINT [FK_dbo.AuthToken_dbo.User_AuthTokenId] FOREIGN KEY ([AuthTokenId]) REFERENCES [dbo].[User] ([UserId])
ALTER TABLE [dbo].[User] ADD CONSTRAINT [FK_dbo.User_dbo.Role_RoleId] FOREIGN KEY ([RoleId]) REFERENCES [dbo].[Role] ([RoleId])
ALTER TABLE [dbo].[Comment] ADD CONSTRAINT [FK_dbo.Comment_dbo.User_UserId] FOREIGN KEY ([UserId]) REFERENCES [dbo].[User] ([UserId])
ALTER TABLE [dbo].[Comment] ADD CONSTRAINT [FK_dbo.Comment_dbo.UserFile_UserFile_UserFileId] FOREIGN KEY ([UserFile_UserFileId]) REFERENCES [dbo].[UserFile] ([UserFileId])
ALTER TABLE [dbo].[Entry] ADD CONSTRAINT [FK_dbo.Entry_dbo.ForumThread_ForumThreadId] FOREIGN KEY ([ForumThreadId]) REFERENCES [dbo].[ForumThread] ([ForumThreadId])
ALTER TABLE [dbo].[Entry] ADD CONSTRAINT [FK_dbo.Entry_dbo.User_UserId] FOREIGN KEY ([UserId]) REFERENCES [dbo].[User] ([UserId])
ALTER TABLE [dbo].[ForumThread] ADD CONSTRAINT [FK_dbo.ForumThread_dbo.User_UserId] FOREIGN KEY ([UserId]) REFERENCES [dbo].[User] ([UserId])
ALTER TABLE [dbo].[UserFile] ADD CONSTRAINT [FK_dbo.UserFile_dbo.User_UserId] FOREIGN KEY ([UserId]) REFERENCES [dbo].[User] ([UserId])