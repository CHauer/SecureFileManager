
/* CREATE TABLE [dbo].[Role] (
    [RoleId] [int] NOT NULL IDENTITY,
    [Name] [nvarchar](max),
    [FileDownload] [bit] NOT NULL,
    [ReadForum] [bit] NOT NULL,
    [ReadComments] [bit] NOT NULL,
    [FileUpload] [bit] NOT NULL,
    [WriteForum] [bit] NOT NULL,
    [WriteComments] [bit] NOT NULL,
    CONSTRAINT [PK_dbo.Role] PRIMARY KEY ([RoleId])
) */

insert into [Role]( [Name], [FileDownload], [ReadForum], [ReadComments], [FileUpload], [WriteForum],[WriteComments]) values('Administrator', 1, 1, 1, 1, 1, 1);
insert into [Role]( [Name], [FileDownload], [ReadForum], [ReadComments], [FileUpload], [WriteForum],[WriteComments]) values('Standard', 1, 1, 1, 0, 0, 0);
insert into [Role]( [Name], [FileDownload], [ReadForum], [ReadComments], [FileUpload], [WriteForum],[WriteComments]) values('Premium', 1, 1, 1, 1, 1, 1);